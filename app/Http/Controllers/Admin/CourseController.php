<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AgeGroup;
use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportCoursesRequest;
use App\Jobs\GenerateCourseWeek;
use App\Jobs\GenerateDayContent;
use App\Models\Course;
use App\Services\CourseImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Courses/Index', [
            'courses' => Course::withCount('coursePrompts', 'enrolledUsers')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Courses/Create', [
            'ageGroups' => AgeGroup::toSelectArray(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|string|max:255',
            'length_in_weeks' => 'required|integer|min:1',
            'age_group' => ['nullable', 'string', Rule::in(array_column(AgeGroup::toSelectArray(), 'value'))],
        ]);

        $courseData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_url' => $validated['image_url'] ?? null,
            'length_in_weeks' => $validated['length_in_weeks'],
        ];

        if (! empty($validated['age_group'])) {
            $ageGroup = AgeGroup::fromValue($validated['age_group']);
            if ($ageGroup) {
                $courseData['min_age'] = $ageGroup->minAge();
                $courseData['max_age'] = $ageGroup->maxAge();
            }
        }

        $course = Course::create($courseData);

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course): Response
    {
        $courseData = $course->load('coursePrompts.days')->toArray();
        $courseData['age_group'] = $course->age_group?->value;
        $courseData['age_group_label'] = $course->age_group_label;
        $courseData['generation_status'] = $course->generation_status?->value;
        $courseData['content_generation_status'] = $course->content_generation_status?->value;

        return Inertia::render('Admin/Courses/Edit', [
            'course' => $courseData,
            'ageGroups' => AgeGroup::toSelectArray(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|string|max:255',
            'length_in_weeks' => 'required|integer|min:1',
            'age_group' => ['nullable', 'string', Rule::in(array_column(AgeGroup::toSelectArray(), 'value'))],
        ]);

        $courseData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_url' => $validated['image_url'] ?? null,
            'length_in_weeks' => $validated['length_in_weeks'],
        ];

        if (! empty($validated['age_group'])) {
            $ageGroup = AgeGroup::fromValue($validated['age_group']);
            if ($ageGroup) {
                $courseData['min_age'] = $ageGroup->minAge();
                $courseData['max_age'] = $ageGroup->maxAge();
            }
        } else {
            $courseData['min_age'] = null;
            $courseData['max_age'] = null;
        }

        $course->update($courseData);

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Queue generation of course weeks and days using AI.
     */
    public function generateWeeks(Request $request, Course $course): JsonResponse
    {
        if ($course->generation_status === ContentStatus::Generating) {
            return response()->json([
                'success' => false,
                'error' => 'Week generation is already in progress.',
            ], 409);
        }

        $daysPerWeek = $request->input('days_per_week', 3);
        $totalWeeks = $course->length_in_weeks ?? 4;

        $course->update(['generation_status' => ContentStatus::Generating]);

        // Delete existing course prompts and their days before queuing
        $course->coursePrompts()->each(function ($prompt) {
            $prompt->days()->delete();
        });
        $course->coursePrompts()->delete();

        GenerateCourseWeek::dispatch($course, 1, $daysPerWeek, $totalWeeks);

        return response()->json([
            'success' => true,
            'message' => 'Week generation has been queued. You will be notified when it completes.',
        ]);
    }

    /**
     * Queue content generation for all days in the course.
     */
    public function generateAllContent(Course $course): JsonResponse
    {
        if ($course->content_generation_status === ContentStatus::Generating) {
            return response()->json([
                'success' => false,
                'error' => 'Content generation is already in progress.',
            ], 409);
        }

        $days = $course->coursePrompts()
            ->with('days')
            ->get()
            ->pluck('days')
            ->flatten();

        if ($days->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'No days found. Generate weeks first.',
            ], 422);
        }

        $course->update(['content_generation_status' => ContentStatus::Generating]);

        foreach ($days as $day) {
            $day->update(['content_status' => ContentStatus::Pending]);
            GenerateDayContent::dispatch($day);
        }

        return response()->json([
            'success' => true,
            'total' => $days->count(),
            'message' => "Queued content generation for {$days->count()} days.",
        ]);
    }

    public function import(ImportCoursesRequest $request, CourseImportService $service): RedirectResponse
    {
        $result = $service->import($request->file('file'));

        if ($result->hasErrors()) {
            return redirect()
                ->route('admin.courses.index')
                ->with('error', 'Import failed: '.implode(' ', $result->validationErrors));
        }

        return redirect()
            ->route('admin.courses.index')
            ->with('success', $result->toFlashMessage());
    }

    public function downloadTemplate(): StreamedResponse
    {
        $writer = Writer::createFromString();
        $writer->insertOne([
            'course_title',
            'course_description',
            'course_min_age',
            'course_max_age',
            'week_number',
            'week_title',
            'week_description',
            'day_number',
            'day_title',
            'day_description',
        ]);
        $writer->insertOne([
            'Intro to Science',
            'A beginner course on scientific concepts.',
            '5',
            '10',
            '1',
            'The Scientific Method',
            'Learn the steps of the scientific method.',
            '1',
            'What is Science?',
            'An introduction to science and discovery.',
        ]);
        $writer->insertOne([
            'Intro to Science',
            'A beginner course on scientific concepts.',
            '5',
            '10',
            '1',
            'The Scientific Method',
            'Learn the steps of the scientific method.',
            '2',
            'Asking Questions',
            'How to formulate scientific questions.',
        ]);

        return response()->streamDownload(function () use ($writer) {
            echo $writer->toString();
        }, 'course-import-template.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Get the current content generation progress for a course.
     */
    public function contentGenerationProgress(Course $course): JsonResponse
    {
        $days = $course->coursePrompts()
            ->with('days')
            ->get()
            ->pluck('days')
            ->flatten();

        $statuses = $days->groupBy(fn ($day) => $day->content_status?->value ?? 'pending');

        return response()->json([
            'total' => $days->count(),
            'pending' => $statuses->get('pending', collect())->count(),
            'generating' => $statuses->get('generating', collect())->count(),
            'completed' => $statuses->get('completed', collect())->count(),
            'failed' => $statuses->get('failed', collect())->count(),
            'days' => $days->map(fn ($day) => [
                'id' => $day->id,
                'day_number' => $day->day_number,
                'week_id' => $day->course_prompt_id,
                'title' => $day->title,
                'content_status' => $day->content_status?->value ?? 'pending',
            ])->values(),
        ]);
    }
}
