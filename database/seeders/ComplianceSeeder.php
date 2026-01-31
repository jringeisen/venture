<?php

namespace Database\Seeders;

use App\Models\ActiveTime;
use App\Models\Course;
use App\Models\LearningSession;
use App\Models\PromptQuestion;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Database\Seeder;

class ComplianceSeeder extends Seeder
{
    /**
     * Seed compliance-related data for the first parent/student pair.
     * Run with: php artisan db:seed --class=ComplianceSeeder
     */
    public function run(): void
    {
        $parent = User::whereNull('parent_id')->first();
        $student = User::where('parent_id', $parent->id)->first();

        if (! $parent || ! $student) {
            $this->command->warn('No parent/student pair found. Skipping compliance seeder.');

            return;
        }

        $this->command->info("Seeding compliance data for parent '{$parent->name}' and student '{$student->name}'...");

        $this->seedCourses($student);
        $this->seedActiveTime($student);
        $this->seedLearningSessions($student);
        $this->seedPromptQuestions($student);

        $this->command->info('Compliance seed data created successfully.');
    }

    private function seedCourses(User $student): void
    {
        $courses = [
            ['title' => 'American History: Founding to Civil War', 'description' => 'Explore the founding of America through the Civil War era.', 'length_in_weeks' => 8, 'min_age' => 8, 'max_age' => 14],
            ['title' => 'Introduction to Earth Science', 'description' => 'Learn about geology, weather, oceans, and ecosystems.', 'length_in_weeks' => 6, 'min_age' => 7, 'max_age' => 13],
            ['title' => 'Creative Writing Workshop', 'description' => 'Develop creative writing skills through guided exercises.', 'length_in_weeks' => 10, 'min_age' => 8, 'max_age' => 16],
        ];

        foreach ($courses as $courseData) {
            $course = Course::firstOrCreate(['title' => $courseData['title']], $courseData);

            if (UserCourse::where('user_id', $student->id)->where('course_id', $course->id)->exists()) {
                continue;
            }

            $weeksCompleted = fake()->numberBetween(1, $course->length_in_weeks);
            $isCompleted = $weeksCompleted >= $course->length_in_weeks;
            $startedAt = now()->subDays(fake()->numberBetween(30, 120));

            $triviaScores = [];
            for ($w = 1; $w <= $weeksCompleted; $w++) {
                $triviaScores["week_{$w}"] = [
                    'score' => fake()->numberBetween(60, 100),
                    'recorded_at' => $startedAt->copy()->addWeeks($w)->toIso8601String(),
                ];
            }

            $weekTimes = [];
            for ($w = 1; $w <= $weeksCompleted; $w++) {
                $weekSeconds = fake()->numberBetween(900, 3600);
                $weekTimes["week_{$w}"] = $weekSeconds;
                for ($d = 1; $d <= fake()->numberBetween(3, 5); $d++) {
                    $weekTimes["week_{$w}_day_{$d}"] = (int) ($weekSeconds / 5);
                }
            }

            UserCourse::create([
                'user_id' => $student->id,
                'course_id' => $course->id,
                'current_week' => $isCompleted ? $course->length_in_weeks : $weeksCompleted,
                'current_day' => $isCompleted ? 5 : fake()->numberBetween(1, 5),
                'started_at' => $startedAt,
                'completed_at' => $isCompleted ? $startedAt->copy()->addWeeks($course->length_in_weeks) : null,
                'last_accessed_at' => now()->subDays(fake()->numberBetween(0, 7)),
                'time_spent_minutes' => collect($weekTimes)->filter(fn ($v, $k) => ! str_contains($k, 'day'))->sum() / 60,
                'week_times' => $weekTimes,
                'trivia_scores' => $triviaScores,
            ]);
        }
    }

    private function seedActiveTime(User $student): void
    {
        $existingDates = ActiveTime::where('user_id', $student->id)->pluck('date')->map(fn ($d) => $d instanceof \Carbon\Carbon ? $d->toDateString() : $d)->toArray();

        for ($i = 90; $i >= 1; $i--) {
            $date = now()->subDays($i)->toDateString();

            if (in_array($date, $existingDates)) {
                continue;
            }

            // ~5 days per week of instruction
            if (fake()->boolean(70)) {
                ActiveTime::create([
                    'user_id' => $student->id,
                    'date' => $date,
                    'total_seconds' => fake()->numberBetween(1800, 10800),
                ]);
            }
        }
    }

    private function seedLearningSessions(User $student): void
    {
        $courseIds = UserCourse::where('user_id', $student->id)->pluck('course_id');

        foreach ($courseIds as $courseId) {
            $existingCount = LearningSession::where('user_id', $student->id)->where('course_id', $courseId)->count();

            if ($existingCount >= 10) {
                continue;
            }

            $sessionsToCreate = max(0, 15 - $existingCount);

            for ($i = 0; $i < $sessionsToCreate; $i++) {
                $startedAt = now()->subDays(fake()->numberBetween(1, 90))->setTime(fake()->numberBetween(8, 16), fake()->numberBetween(0, 59));
                $durationSeconds = fake()->numberBetween(300, 3600);

                LearningSession::create([
                    'user_id' => $student->id,
                    'course_id' => $courseId,
                    'week_number' => fake()->numberBetween(1, 8),
                    'day_number' => fake()->numberBetween(1, 5),
                    'started_at' => $startedAt,
                    'ended_at' => $startedAt->copy()->addSeconds($durationSeconds),
                    'duration_seconds' => $durationSeconds,
                    'is_active' => false,
                ]);
            }
        }
    }

    private function seedPromptQuestions(User $student): void
    {
        $existingCount = PromptQuestion::where('user_id', $student->id)->count();

        if ($existingCount >= 15) {
            return;
        }

        $questions = [
            'What caused the American Revolution?',
            'Explain the water cycle and why it matters.',
            'How do volcanoes form?',
            'What is the difference between weather and climate?',
            'Describe the three branches of government.',
            'What is photosynthesis and why is it important?',
            'How did the Industrial Revolution change daily life?',
            'What are the main types of rocks?',
            'Explain how the Constitution protects individual rights.',
            'What role do oceans play in regulating Earth\'s temperature?',
            'Write a short story about an adventure in space.',
            'What is erosion and how does it shape the land?',
            'Describe the life cycle of a butterfly.',
            'How did the Civil War affect the United States?',
            'What are renewable energy sources?',
        ];

        $questionsToCreate = max(0, 15 - $existingCount);
        $selectedQuestions = array_slice($questions, 0, $questionsToCreate);

        foreach ($selectedQuestions as $i => $questionText) {
            $createdAt = now()->subDays(fake()->numberBetween(1, 90));

            $question = PromptQuestion::withoutEvents(function () use ($student, $questionText, $createdAt) {
                return $student->promptQuestions()->create([
                    'question' => $questionText,
                    'total_tokens' => fake()->numberBetween(200, 2000),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            });

            $question->promptAnswer()->create([
                'content' => fake()->paragraphs(fake()->numberBetween(2, 5), true),
                'word_count' => fake()->numberBetween(100, 800),
                'subject_category' => fake()->randomElement(['History', 'Science', 'Language Arts', 'Geography', 'Creative Writing']),
            ]);
        }
    }
}
