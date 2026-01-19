<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePrompt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CoursePromptController extends Controller
{
    public function create(Course $course): Response
    {
        $nextWeek = $course->coursePrompts()->max('week_number') + 1 ?: 1;

        return Inertia::render('Admin/CoursePrompts/Create', [
            'course' => $course,
            'nextWeek' => $nextWeek,
        ]);
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'week_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prompt_text' => 'nullable|string',
            'content' => 'nullable|string',
            'learning_objectives' => 'nullable|array',
            'trivia_questions' => 'nullable|array',
            'additional_resources' => 'nullable|array',
            'estimated_duration_minutes' => 'nullable|integer|min:1',
        ]);

        $course->coursePrompts()->create($validated);

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Week added successfully.');
    }

    public function edit(Course $course, CoursePrompt $prompt): Response
    {
        // Transform trivia questions from Nova Repeater format to flat format
        $triviaQuestions = collect($prompt->trivia_questions ?? [])
            ->map(function ($question) {
                $fields = $question['fields'] ?? $question;

                return [
                    'question' => $fields['question'] ?? '',
                    'option_a' => $fields['option_a'] ?? '',
                    'option_b' => $fields['option_b'] ?? '',
                    'option_c' => $fields['option_c'] ?? '',
                    'option_d' => $fields['option_d'] ?? '',
                    'correct_answer' => (int) ($fields['correct_answer'] ?? 0),
                ];
            })
            ->values()
            ->toArray();

        return Inertia::render('Admin/CoursePrompts/Edit', [
            'course' => $course,
            'prompt' => array_merge($prompt->toArray(), [
                'trivia_questions' => $triviaQuestions,
            ]),
        ]);
    }

    public function update(Request $request, Course $course, CoursePrompt $prompt): RedirectResponse
    {
        $validated = $request->validate([
            'week_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prompt_text' => 'nullable|string',
            'content' => 'nullable|string',
            'learning_objectives' => 'nullable|array',
            'trivia_questions' => 'nullable|array',
            'additional_resources' => 'nullable|array',
            'estimated_duration_minutes' => 'nullable|integer|min:1',
        ]);

        $prompt->update($validated);

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Week updated successfully.');
    }

    public function destroy(Course $course, CoursePrompt $prompt): RedirectResponse
    {
        $prompt->delete();

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Week deleted successfully.');
    }
}
