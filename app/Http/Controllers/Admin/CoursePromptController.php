<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePrompt;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use OpenAI\Laravel\Facades\OpenAI;

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

    public function generateContent(Request $request, Course $course, CoursePrompt $prompt): StreamedResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        $title = $validated['title'];
        $description = $validated['description'] ?? '';
        $durationMinutes = $validated['duration_minutes'] ?? 30;

        // Calculate approximate word count based on duration (average reading speed ~200 words/min for students)
        $targetWordCount = $durationMinutes * 150; // Slightly slower for educational content with comprehension
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

Return your response in the following JSON format:
{
    "content": "<h2>...</h2><p>...</p>...",
    "trivia_questions": [
        {
            "question": "Question text here?",
            "option_a": "First option",
            "option_b": "Second option",
            "option_c": "Third option",
            "option_d": "Fourth option",
            "correct_answer": 0
        }
    ]
}

Note: correct_answer should be 0 for A, 1 for B, 2 for C, or 3 for D.

Important: Return ONLY the JSON object, no additional text or markdown code blocks.
PROMPT;

        // Increase execution time for AI generation
        set_time_limit(300);

        return response()->stream(function () use ($promptText) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            try {
                $stream = OpenAI::chat()->createStreamed([
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert K-12 educator creating engaging educational content. Always respond with valid JSON only.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $promptText,
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

                // Send the complete parsed response at the end
                $data = json_decode($fullContent, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    echo "data: " . json_encode([
                        'done' => true,
                        'content' => $data['content'] ?? '',
                        'trivia_questions' => $data['trivia_questions'] ?? [],
                    ]) . "\n\n";
                } else {
                    echo "data: " . json_encode([
                        'done' => true,
                        'error' => 'Failed to parse response',
                    ]) . "\n\n";
                }
                ob_flush();
                flush();

            } catch (\Exception $e) {
                Log::error('Failed to generate content', ['error' => $e->getMessage()]);
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
