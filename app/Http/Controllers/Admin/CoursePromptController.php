<?php

namespace App\Http\Controllers\Admin;

use App\Ai\Agents\LessonContentGenerationAgent;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePrompt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'days_count' => 'nullable|integer|min:1|max:7',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_duration_minutes' => 'nullable|integer|min:1',
        ]);

        $validated['days_count'] = $validated['days_count'] ?? 5;

        $course->coursePrompts()->create($validated);

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Week added successfully.');
    }

    public function edit(Course $course, CoursePrompt $prompt): Response
    {
        // Load days relationship
        $prompt->load('days');

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
            'days_count' => 'nullable|integer|min:1|max:7',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'trivia_questions' => 'nullable|array',
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

    public function generateContent(Request $request, Course $course, CoursePrompt $prompt): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        $title = $validated['title'];
        $description = $validated['description'] ?? '';
        $durationMinutes = $validated['duration_minutes'] ?? 30;

        // Calculate approximate word count based on duration
        $targetWordCount = $durationMinutes * 150;
        $minWords = max(500, (int) ($targetWordCount * 0.8));
        $maxWords = (int) ($targetWordCount * 1.2);

        $promptText = <<<PROMPT
Generate educational content for K-12 students based on the following information:

**Title:** {$title}

**Description:** {$description}

**Target Duration:** {$durationMinutes} minutes of reading/learning time

Please generate:

1. **Educational Content**: Create engaging, age-appropriate educational content that covers the topic thoroughly. The content should take approximately {$durationMinutes} minutes to read and understand, so aim for {$minWords}-{$maxWords} words. Include:
   - A compelling introduction
   - Clear explanations with real-world examples and analogies
   - Key concepts broken down into digestible sections
   - Practical applications or activities where appropriate
   - A summary of key takeaways

   Format the content with proper HTML tags (h2, h3, p, ul, li, strong, em) for good readability.

2. **Trivia Questions**: Generate exactly 6 multiple choice trivia questions to test comprehension. Each question should have 4 options (A, B, C, D) with exactly one correct answer. Questions should cover the main concepts from the content.

Note: correct_answer should be 0 for A, 1 for B, 2 for C, or 3 for D.
PROMPT;

        set_time_limit(300);

        try {
            $response = LessonContentGenerationAgent::make()->prompt($promptText);

            return response()->json([
                'success' => true,
                'content' => $response['content'] ?? '',
                'trivia_questions' => $response['trivia_questions'] ?? [],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate content', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
