<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AgeGroup;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseDay;
use App\Models\CoursePrompt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseDayController extends Controller
{
    public function create(Course $course, CoursePrompt $prompt): Response
    {
        $nextDay = $prompt->days()->max('day_number') + 1 ?: 1;

        return Inertia::render('Admin/CourseDays/Create', [
            'course' => $course,
            'prompt' => $prompt,
            'nextDay' => $nextDay,
        ]);
    }

    public function store(Request $request, Course $course, CoursePrompt $prompt): RedirectResponse
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'learning_objectives' => 'nullable|array',
            'trivia_questions' => 'nullable|array',
            'estimated_duration_minutes' => 'nullable|integer|min:1',
        ]);

        $prompt->days()->create($validated);

        return redirect()
            ->route('admin.courses.weeks.edit', [$course, $prompt])
            ->with('success', 'Day added successfully.');
    }

    public function edit(Course $course, CoursePrompt $prompt, CourseDay $day): Response
    {
        // Transform trivia questions from stored format to flat format
        $triviaQuestions = collect($day->trivia_questions ?? [])
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

        return Inertia::render('Admin/CourseDays/Edit', [
            'course' => $course,
            'prompt' => $prompt,
            'day' => array_merge($day->toArray(), [
                'trivia_questions' => $triviaQuestions,
            ]),
        ]);
    }

    public function update(Request $request, Course $course, CoursePrompt $prompt, CourseDay $day): RedirectResponse
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'learning_objectives' => 'nullable|array',
            'trivia_questions' => 'nullable|array',
            'estimated_duration_minutes' => 'nullable|integer|min:1',
        ]);

        $day->update($validated);

        return redirect()
            ->route('admin.courses.weeks.edit', [$course, $prompt])
            ->with('success', 'Day updated successfully.');
    }

    public function destroy(Course $course, CoursePrompt $prompt, CourseDay $day): RedirectResponse
    {
        $day->delete();

        return redirect()
            ->route('admin.courses.weeks.edit', [$course, $prompt])
            ->with('success', 'Day deleted successfully.');
    }

    public function generateContent(Request $request, Course $course, CoursePrompt $prompt, CourseDay $day): StreamedResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'learning_objectives' => 'nullable|array',
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        $title = $validated['title'];
        $description = $validated['description'] ?? '';
        $learningObjectives = $validated['learning_objectives'] ?? [];
        $durationMinutes = $validated['duration_minutes'] ?? 15;

        $objectivesList = collect($learningObjectives)
            ->filter()
            ->map(fn ($obj, $i) => ($i + 1) . '. ' . $obj)
            ->implode("\n");

        // Calculate approximate word count based on duration
        // Using 200 words/min for comprehensive educational content with activities
        $targetWordCount = $durationMinutes * 200;
        $minWords = max(500, (int) ($targetWordCount * 0.85));
        $maxWords = (int) ($targetWordCount * 1.3);

        // Get age group context and guidelines
        $ageGroupContext = '';
        $ageGroupGuidelines = '';
        $ageGroup = $course->age_group;
        if ($ageGroup) {
            $ageGroupLabel = $ageGroup->label();
            $ageGroupGuidelines = $ageGroup->contentGuidelines();
            $ageGroupContext = "- **Target Age Group:** {$ageGroupLabel}";
        } else {
            $ageGroupContext = "- **Target Age Group:** All Ages (General K-12)";
        }

        $ageGuidanceSection = '';
        if ($ageGroupGuidelines) {
            $ageGuidanceSection = <<<AGEGUIDANCE

## Age-Appropriate Content Guidelines
Follow these guidelines carefully to ensure content matches the target age group:

{$ageGroupGuidelines}
AGEGUIDANCE;
        }

        $aiPrompt = <<<PROMPT
You are an expert K-12 curriculum designer creating a comprehensive, engaging lesson. Generate thorough educational content that will genuinely teach students about this topic.

## Lesson Context
- **Course:** {$course->title}
- **Week:** {$prompt->title} (Week {$prompt->week_number})
- **Day:** {$title} (Day {$day->day_number})
- **Topic Description:** {$description}
- **Target Reading Time:** {$durationMinutes} minutes ({$minWords}-{$maxWords} words)
{$ageGroupContext}
{$ageGuidanceSection}

## Learning Objectives
Students should be able to:
{$objectivesList}

## Content Requirements

Create a COMPREHENSIVE lesson with ALL of the following sections:

### 1. Hook & Introduction
- Start with an attention-grabbing hook (surprising fact, thought-provoking question, or relatable scenario)
- Explain WHY this topic matters and how it connects to students' lives
- Provide a brief roadmap of what they'll learn

### 2. Key Vocabulary (3-5 terms)
- Define essential terms students need to understand the lesson
- Use simple, clear definitions with examples
- Format as a visually distinct vocabulary box

### 3. Core Content (This should be the bulk of the lesson)
Structure the main content with:
- **Concept Introduction**: Start with the basics, assume no prior knowledge
- **Detailed Explanations**: Break down complex ideas into understandable parts
- **Real-World Examples**: At least 2-3 concrete, relatable examples for each major concept
- **Analogies**: Use comparisons to familiar things to explain abstract concepts
- **Visual Descriptions**: Describe diagrams, processes, or scenarios students should visualize
- **Historical Context or Background**: Where did this knowledge come from? Who discovered it?
- **Step-by-Step Breakdowns**: For any processes or procedures, provide clear numbered steps

### 4. Fun Facts & Did You Know?
- Include 2-3 interesting, memorable facts related to the topic
- These should be genuinely surprising or fascinating

### 5. Real-World Connections
- How is this used in careers or everyday life?
- Current events or modern applications
- How might students encounter this outside of school?

### 6. Hands-On Activity or Thought Experiment
- Suggest a simple activity students can do to reinforce learning
- Or provide a thought experiment / mental exercise
- Should be doable without special materials

### 7. Check Your Understanding
- 2-3 reflection questions (not trivia) that encourage deeper thinking
- These should be open-ended, not multiple choice

### 8. Summary & Key Takeaways
- Bullet-point summary of the most important concepts
- Reinforce the learning objectives
- Preview how this connects to future lessons (if applicable)

## Formatting Guidelines
- Use proper HTML: h2 for main sections, h3 for subsections, p for paragraphs
- Use ul/li for lists, strong for key terms, em for emphasis
- Use blockquote for fun facts or callout boxes
- Make content scannable with clear headings and short paragraphs
- Include visual breaks between sections

## Trivia Questions
Generate exactly 5 multiple choice questions with varying difficulty:
- 2 Easy (recall/remember) - Basic facts from the lesson
- 2 Medium (understand/apply) - Applying concepts to new situations
- 1 Hard (analyze/evaluate) - Requires deeper thinking or combining concepts

Each question must:
- Be clearly worded and unambiguous
- Have exactly one correct answer
- Include plausible distractors (wrong answers should be reasonable, not obviously wrong)
- Cover different parts of the lesson content

## JSON Response Format
{
    "content": "<h2>Hook Title</h2><p>Opening hook...</p>...(full HTML content with all sections)...",
    "trivia_questions": [
        {
            "question": "Question text?",
            "option_a": "First option",
            "option_b": "Second option",
            "option_c": "Third option",
            "option_d": "Fourth option",
            "correct_answer": 0,
            "difficulty": "easy"
        }
    ]
}

Note: correct_answer is 0 for A, 1 for B, 2 for C, or 3 for D.

CRITICAL: Return ONLY valid JSON. No markdown code blocks, no extra text. The content field should contain well-structured HTML that covers ALL required sections thoroughly.
PROMPT;

        set_time_limit(300);

        return response()->stream(function () use ($aiPrompt) {
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
                            'content' => 'You are an expert K-12 curriculum designer and educator with deep knowledge of pedagogy, child development, and engaging content creation. You create comprehensive, well-structured lessons that genuinely teach students. Your content is thorough, uses multiple teaching strategies (examples, analogies, visuals, activities), and makes learning memorable and enjoyable. Always respond with valid JSON only.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $aiPrompt,
                        ],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'max_tokens' => 12000,
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
                Log::error('Failed to generate day content', ['error' => $e->getMessage()]);
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
