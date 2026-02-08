<?php

namespace App\Jobs;

use App\Ai\Agents\SingleCourseWeekGenerationAgent;
use App\Enums\ContentStatus;
use App\Events\CourseWeekCreated;
use App\Events\CourseWeeksGenerated;
use App\Models\Course;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateCourseWeek implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300;

    public int $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Course $course,
        public int $weekNumber,
        public int $daysPerWeek,
        public int $totalWeeks,
    ) {
        $this->onQueue('content-generation');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $course = $this->course;

        $ageGroupContext = '';
        $ageGroup = $course->age_group;
        if ($ageGroup) {
            $ageGroupLabel = $ageGroup->label();
            $ageGroupContext = "\n**Target Age Group:** {$ageGroupLabel}\n\nIMPORTANT: All content must be specifically designed for this age group. Adjust vocabulary, complexity, examples, and activities accordingly.";
        } else {
            $ageGroupContext = "\n**Target Age Group:** All Ages (General K-12)\n\nDesign content that can be adapted for various age levels.";
        }

        $previousWeeksContext = $this->buildPreviousWeeksContext();

        $prompt = <<<PROMPT
You are an expert K-12 curriculum designer. Create the outline for **Week {$this->weekNumber}** of a {$this->totalWeeks}-week course:

**Course Title:** {$course->title}

**Course Description:** {$course->description}
{$ageGroupContext}

**Week Number:** {$this->weekNumber} of {$this->totalWeeks}
**Days per Week:** {$this->daysPerWeek}
{$previousWeeksContext}

Generate for this single week:
1. A compelling title for the week's topic
2. A brief description (2-3 sentences) of what students will learn
3. 3-5 specific learning objectives for the week overall
4. For each day within the week:
   - A title for the day's lesson
   - A brief description of what students will learn that day
   - 2-3 specific learning objectives for that day
   - Estimated duration in minutes (typically 10-20 minutes per day)

This is week {$this->weekNumber} of {$this->totalWeeks}. Earlier weeks should cover foundational concepts while later weeks progress to more advanced topics. Make the content engaging and age-appropriate for the target age group.

Generate exactly 1 week with exactly {$this->daysPerWeek} days.
PROMPT;

        try {
            $weekData = SingleCourseWeekGenerationAgent::make()->prompt($prompt);

            $week = $course->coursePrompts()->create([
                'week_number' => $this->weekNumber,
                'days_count' => count($weekData['days'] ?? []) ?: $this->daysPerWeek,
                'title' => $weekData['title'],
                'description' => $weekData['description'],
                'learning_objectives' => $weekData['learning_objectives'] ?? [],
                'estimated_duration_minutes' => $weekData['estimated_duration_minutes'] ?? 30,
            ]);

            $days = [];
            foreach ($weekData['days'] ?? [] as $dayData) {
                $day = $week->days()->create([
                    'day_number' => $dayData['day_number'],
                    'title' => $dayData['title'],
                    'description' => $dayData['description'] ?? '',
                    'learning_objectives' => $dayData['learning_objectives'] ?? [],
                    'estimated_duration_minutes' => $dayData['estimated_duration_minutes'] ?? 15,
                ]);
                $days[] = [
                    'id' => $day->id,
                    'day_number' => $day->day_number,
                    'title' => $day->title,
                    'description' => $day->description,
                    'content_status' => 'pending',
                ];
            }

            broadcast(new CourseWeekCreated(
                courseId: $course->id,
                weekData: [
                    'id' => $week->id,
                    'week_number' => $week->week_number,
                    'title' => $week->title,
                    'description' => $week->description,
                    'days_count' => $week->days_count,
                    'days' => $days,
                ],
            ));

            if ($this->weekNumber < $this->totalWeeks) {
                dispatch(new GenerateCourseWeek(
                    $course,
                    $this->weekNumber + 1,
                    $this->daysPerWeek,
                    $this->totalWeeks,
                ));
            } else {
                $course->update(['generation_status' => ContentStatus::Completed]);

                broadcast(new CourseWeeksGenerated(
                    courseId: $course->id,
                    status: 'completed',
                    message: "Successfully generated {$this->totalWeeks} weeks",
                ));
            }
        } catch (\Exception $e) {
            Log::error('Failed to generate course week', [
                'course_id' => $course->id,
                'week_number' => $this->weekNumber,
                'error' => $e->getMessage(),
            ]);

            $course->update(['generation_status' => ContentStatus::Failed]);

            broadcast(new CourseWeeksGenerated(
                courseId: $course->id,
                status: 'failed',
                message: 'Failed to generate one or more weeks',
            ));

            throw $e;
        }
    }

    /**
     * Build context string from previously generated weeks to avoid duplicate content.
     */
    private function buildPreviousWeeksContext(): string
    {
        $existingWeeks = $this->course->coursePrompts()
            ->with('days')
            ->orderBy('week_number')
            ->get();

        if ($existingWeeks->isEmpty()) {
            return '';
        }

        $context = "\n\n**PREVIOUSLY GENERATED WEEKS (DO NOT duplicate these topics or content):**\n";

        foreach ($existingWeeks as $week) {
            $context .= "\n- Week {$week->week_number}: {$week->title}";
            foreach ($week->days as $day) {
                $context .= "\n  - Day {$day->day_number}: {$day->title}";
            }
        }

        $context .= "\n\nIMPORTANT: Generate completely different topics and content from the weeks listed above. Do not repeat or overlap with any previously covered material.";

        return $context;
    }
}
