<?php

namespace App\Jobs;

use App\Ai\Agents\CourseWeekGenerationAgent;
use App\Events\CourseWeeksGenerated;
use App\Models\Course;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateCourseWeeks implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300;

    public int $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Course $course,
        public int $daysPerWeek = 5,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $course = $this->course;
        $numberOfWeeks = $course->length_in_weeks ?? 4;
        $daysPerWeek = $this->daysPerWeek;

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

**Course Title:** {$course->title}

**Course Description:** {$course->description}
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

Generate exactly {$numberOfWeeks} weeks with exactly {$daysPerWeek} days each.
PROMPT;

        try {
            $response = CourseWeekGenerationAgent::make()->prompt($prompt);

            $weeks = $response['weeks'] ?? [];

            // Delete existing course prompts and their days
            $course->coursePrompts()->each(function ($prompt) {
                $prompt->days()->delete();
            });
            $course->coursePrompts()->delete();

            // Create the new weeks with days
            $totalDays = 0;
            foreach ($weeks as $weekData) {
                $week = $course->coursePrompts()->create([
                    'week_number' => $weekData['week_number'],
                    'days_count' => count($weekData['days'] ?? []) ?: $daysPerWeek,
                    'title' => $weekData['title'],
                    'description' => $weekData['description'],
                    'learning_objectives' => $weekData['learning_objectives'] ?? [],
                    'estimated_duration_minutes' => $weekData['estimated_duration_minutes'] ?? 30,
                ]);

                foreach ($weekData['days'] ?? [] as $dayData) {
                    $week->days()->create([
                        'day_number' => $dayData['day_number'],
                        'title' => $dayData['title'],
                        'description' => $dayData['description'] ?? '',
                        'learning_objectives' => $dayData['learning_objectives'] ?? [],
                        'estimated_duration_minutes' => $dayData['estimated_duration_minutes'] ?? 15,
                    ]);
                    $totalDays++;
                }
            }

            broadcast(new CourseWeeksGenerated(
                courseId: $course->id,
                status: 'completed',
                message: 'Successfully generated '.count($weeks).' weeks with '.$totalDays.' days',
            ));
        } catch (\Exception $e) {
            Log::error('Failed to generate course weeks', [
                'course_id' => $course->id,
                'error' => $e->getMessage(),
            ]);

            broadcast(new CourseWeeksGenerated(
                courseId: $course->id,
                status: 'failed',
                message: 'Failed to generate weeks: '.$e->getMessage(),
            ));
        }
    }
}
