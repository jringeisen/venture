<?php

namespace App\Jobs;

use App\Ai\Agents\LessonContentGenerationAgent;
use App\Enums\ContentStatus;
use App\Events\DayContentGenerated;
use App\Models\Course;
use App\Models\CourseDay;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateDayContent implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300;

    public int $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(public CourseDay $day)
    {
        $this->onQueue('content-generation');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->day->update(['content_status' => ContentStatus::Generating]);

        $week = $this->day->week;
        $course = $week->course;

        try {
            $prompt = $this->buildPrompt($course, $week, $this->day);

            $response = LessonContentGenerationAgent::make()->prompt($prompt);

            $this->day->update([
                'content' => $response['content'] ?? '',
                'trivia_questions' => $response['trivia_questions'] ?? [],
                'content_status' => ContentStatus::Completed,
            ]);

            $this->broadcastStatus('completed');
            $this->updateCourseContentStatus($course);
        } catch (\Exception $e) {
            Log::error('Failed to generate day content', [
                'day_id' => $this->day->id,
                'error' => $e->getMessage(),
            ]);

            $this->day->update(['content_status' => ContentStatus::Failed]);

            $this->broadcastStatus('failed');
            $this->updateCourseContentStatus($course);
        }
    }

    /**
     * Build the AI prompt for content generation.
     */
    protected function buildPrompt($course, $week, CourseDay $day): string
    {
        $title = $day->title;
        $description = $day->description ?? '';
        $learningObjectives = $day->learning_objectives ?? [];
        $durationMinutes = $day->estimated_duration_minutes ?? 15;

        $objectivesList = collect($learningObjectives)
            ->filter()
            ->map(fn ($obj, $i) => ($i + 1).'. '.$obj)
            ->implode("\n");

        $targetWordCount = $durationMinutes * 75;
        $minWords = max(500, (int) ($targetWordCount * 0.85));
        $maxWords = (int) ($targetWordCount * 1.3);

        $ageGroupContext = '';
        $ageGroupGuidelines = '';
        $ageGroup = $course->age_group;
        if ($ageGroup) {
            $ageGroupLabel = $ageGroup->label();
            $ageGroupGuidelines = $ageGroup->contentGuidelines();
            $ageGroupContext = "- **Target Age Group:** {$ageGroupLabel}";
        } else {
            $ageGroupContext = '- **Target Age Group:** All Ages (General K-12)';
        }

        $ageGuidanceSection = '';
        if ($ageGroupGuidelines) {
            $ageGuidanceSection = <<<AGEGUIDANCE

## Age-Appropriate Content Guidelines
Follow these guidelines carefully to ensure content matches the target age group:

{$ageGroupGuidelines}
AGEGUIDANCE;
        }

        return <<<PROMPT
You are an expert K-12 curriculum designer creating a comprehensive, engaging lesson. Generate thorough educational content that will genuinely teach students about this topic.

## Lesson Context
- **Course:** {$course->title}
- **Week:** {$week->title} (Week {$week->week_number})
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

**IMPORTANT: Do NOT include any hands-on activities, classroom experiments, physical projects, or thought experiments. This is a reading-only lesson — focus entirely on explaining concepts.**

### 4. Fun Facts & Did You Know?
- Include 2-3 interesting, memorable facts related to the topic
- These should be genuinely surprising or fascinating

### 5. Real-World Connections
- How is this used in careers or everyday life?
- Current events or modern applications
- How might students encounter this outside of school?

### 6. Check Your Understanding
- 2-3 reflection questions (not trivia) that encourage deeper thinking
- These should be open-ended, not multiple choice

### 7. Summary & Key Takeaways
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

Note: correct_answer is 0 for A, 1 for B, 2 for C, or 3 for D.
PROMPT;
    }

    /**
     * Check if all sibling days are terminal and update the course content_generation_status.
     */
    protected function updateCourseContentStatus(Course $course): void
    {
        $allDays = CourseDay::query()
            ->whereHas('week', fn ($q) => $q->where('course_id', $course->id))
            ->get();

        $allTerminal = $allDays->every(
            fn ($day) => in_array($day->content_status, [ContentStatus::Completed, ContentStatus::Failed])
        );

        if (! $allTerminal) {
            return;
        }

        $anyFailed = $allDays->contains(fn ($day) => $day->content_status === ContentStatus::Failed);

        $course->update([
            'content_generation_status' => $anyFailed ? ContentStatus::Failed : ContentStatus::Completed,
        ]);
    }

    /**
     * Broadcast the content generation status.
     */
    protected function broadcastStatus(string $status): void
    {
        $week = $this->day->week;

        broadcast(new DayContentGenerated(
            courseId: $week->course_id,
            weekId: $week->id,
            dayId: $this->day->id,
            status: $status,
            dayNumber: $this->day->day_number,
            weekNumber: $week->week_number,
        ));
    }
}
