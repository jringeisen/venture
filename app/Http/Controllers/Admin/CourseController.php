<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AgeGroup;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use OpenAI\Laravel\Facades\OpenAI;
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
        $courseData = $course->load('coursePrompts')->toArray();
        $courseData['age_group'] = $course->age_group?->value;
        $courseData['age_group_label'] = $course->age_group_label;

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
     * Generate course weeks and days using AI based on course title and description
     */
    public function generateWeeks(Request $request, Course $course): StreamedResponse
    {
        $numberOfWeeks = $course->length_in_weeks ?? 4;
        $daysPerWeek = $request->input('days_per_week', 5);
        $title = $course->title;
        $description = $course->description;

        $ageGroupContext = '';
        $ageGroup = $course->age_group;
        if ($ageGroup) {
            $ageGroupLabel = $ageGroup->label();
            $ageGroupContext = "\n**Target Age Group:** {$ageGroupLabel}\n\nIMPORTANT: All content must be specifically designed for this age group. Adjust vocabulary, complexity, examples, and activities accordingly.";
        } else {
            $ageGroupContext = "\n**Target Age Group:** All Ages (General K-12)\n\nDesign content that can be adapted for various age levels.";
        }

        $prompt = <<<PROMPT
You are an expert K-12 curriculum designer. Create a comprehensive course outline for the following course:

**Course Title:** {$title}

**Course Description:** {$description}
{$ageGroupContext}

**Number of Weeks:** {$numberOfWeeks}
**Days per Week:** {$daysPerWeek}

For each week, generate:
1. A compelling title for the week's topic
2. A brief description (2-3 sentences) of what students will learn
3. 3-5 specific learning objectives for the week overall
4. For each day within the week:
   - A title for the day's lesson
   - A brief description of what students will learn that day
   - 2-3 specific learning objectives for that day
   - Estimated duration in minutes (typically 10-20 minutes per day)

The weeks should build upon each other logically, progressing from foundational concepts to more advanced topics. Days within a week should break down the week's topic into digestible daily lessons. Make the content engaging and age-appropriate for the target age group.

Return your response in the following JSON format:
{
    "weeks": [
        {
            "week_number": 1,
            "title": "Week title here",
            "description": "Brief description of what students will learn this week.",
            "learning_objectives": [
                "First learning objective",
                "Second learning objective",
                "Third learning objective"
            ],
            "days": [
                {
                    "day_number": 1,
                    "title": "Day 1 lesson title",
                    "description": "Brief description of Day 1's focus.",
                    "learning_objectives": [
                        "Day 1 objective 1",
                        "Day 1 objective 2"
                    ],
                    "estimated_duration_minutes": 15
                }
            ]
        }
    ]
}

Generate exactly {$numberOfWeeks} weeks with exactly {$daysPerWeek} days each. Return ONLY the JSON object, no additional text or markdown code blocks.
PROMPT;

        set_time_limit(300);

        return response()->stream(function () use ($prompt, $course, $numberOfWeeks, $daysPerWeek) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            try {
                // Send initial status
                echo "data: " . json_encode(['status' => 'generating', 'message' => "Generating {$numberOfWeeks} weeks with {$daysPerWeek} days each..."]) . "\n\n";
                ob_flush();
                flush();

                $stream = OpenAI::chat()->createStreamed([
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert K-12 curriculum designer. Always respond with valid JSON only.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'max_tokens' => 16000,
                ]);

                $fullContent = '';

                foreach ($stream as $response) {
                    $chunk = $response->choices[0]->delta->content ?? '';
                    if ($chunk !== '') {
                        $fullContent .= $chunk;
                        echo "data: " . json_encode(['chunk' => $chunk]) . "\n\n";
                        ob_flush();
                        flush();
                    }
                }

                // Parse the response and create the weeks
                $data = json_decode($fullContent, true);

                if (json_last_error() !== JSON_ERROR_NONE || !isset($data['weeks'])) {
                    echo "data: " . json_encode([
                        'done' => true,
                        'error' => 'Failed to parse AI response',
                    ]) . "\n\n";
                    ob_flush();
                    flush();
                    return;
                }

                // Delete existing course prompts and their days
                $course->coursePrompts()->each(function ($prompt) {
                    $prompt->days()->delete();
                });
                $course->coursePrompts()->delete();

                // Create the new weeks with days
                $createdWeeks = [];
                foreach ($data['weeks'] as $weekData) {
                    $week = $course->coursePrompts()->create([
                        'week_number' => $weekData['week_number'],
                        'days_count' => count($weekData['days'] ?? []) ?: $daysPerWeek,
                        'title' => $weekData['title'],
                        'description' => $weekData['description'],
                        'learning_objectives' => $weekData['learning_objectives'] ?? [],
                        'estimated_duration_minutes' => $weekData['estimated_duration_minutes'] ?? 30,
                    ]);

                    // Create days for this week
                    $createdDays = [];
                    foreach ($weekData['days'] ?? [] as $dayData) {
                        $day = $week->days()->create([
                            'day_number' => $dayData['day_number'],
                            'title' => $dayData['title'],
                            'description' => $dayData['description'] ?? '',
                            'learning_objectives' => $dayData['learning_objectives'] ?? [],
                            'estimated_duration_minutes' => $dayData['estimated_duration_minutes'] ?? 15,
                        ]);
                        $createdDays[] = $day->toArray();
                    }

                    $weekArray = $week->toArray();
                    $weekArray['days'] = $createdDays;
                    $createdWeeks[] = $weekArray;

                    echo "data: " . json_encode([
                        'status' => 'created',
                        'week' => $weekArray,
                    ]) . "\n\n";
                    ob_flush();
                    flush();
                }

                $totalDays = array_sum(array_map(fn($w) => count($w['days'] ?? []), $createdWeeks));
                echo "data: " . json_encode([
                    'done' => true,
                    'weeks' => $createdWeeks,
                    'message' => 'Successfully generated ' . count($createdWeeks) . ' weeks with ' . $totalDays . ' days',
                ]) . "\n\n";
                ob_flush();
                flush();

            } catch (\Exception $e) {
                Log::error('Failed to generate course weeks', ['error' => $e->getMessage()]);
                echo "data: " . json_encode(['done' => true, 'error' => $e->getMessage()]) . "\n\n";
                ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
