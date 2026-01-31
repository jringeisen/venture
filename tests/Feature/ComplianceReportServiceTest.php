<?php

use App\Enums\ComplianceState;
use App\Models\ActiveTime;
use App\Models\Course;
use App\Models\PromptQuestion;
use App\Models\User;
use App\Models\UserCourse;
use App\Services\ComplianceReportService;

beforeEach(function () {
    $this->service = new ComplianceReportService;
    $this->parent = User::factory()->parent()->create();
    $this->student = User::factory()->create([
        'parent_id' => $this->parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);
    $this->start = now()->subMonths(3);
    $this->end = now();
});

it('generates a compliance report with all sections', function () {
    $report = $this->service->generateReport($this->student, $this->start, $this->end);

    expect($report->parent_id)->toBe($this->parent->id);
    expect($report->student_id)->toBe($this->student->id);
    expect($report->state)->toBe(ComplianceState::FL);
    expect($report->summary_statistics)->toBeArray();
    expect($report->report_metadata)->toBeArray();
    expect($report->report_metadata)->toHaveKeys([
        'daily_activity_log',
        'course_progress',
        'assessment_results',
        'interaction_log',
    ]);
});

it('uses custom title when provided', function () {
    $report = $this->service->generateReport($this->student, $this->start, $this->end, ComplianceState::FL, 'My Custom Title');

    expect($report->title)->toBe('My Custom Title');
});

it('generates default title when none provided', function () {
    $report = $this->service->generateReport($this->student, $this->start, $this->end);

    expect($report->title)->toContain('Florida Compliance Report');
});

it('returns daily activity log from active time entries', function () {
    ActiveTime::create([
        'user_id' => $this->student->id,
        'date' => now()->subDays(5)->toDateString(),
        'total_seconds' => 3600,
    ]);

    ActiveTime::create([
        'user_id' => $this->student->id,
        'date' => now()->subDays(3)->toDateString(),
        'total_seconds' => 1800,
    ]);

    $log = $this->service->getDailyActivityLog($this->student, $this->start, $this->end);

    expect($log)->toHaveCount(2);
    expect($log[0])->toHaveKeys(['date', 'total_seconds', 'courses_studied', 'sessions']);
});

it('returns course progress for enrolled courses', function () {
    $course = Course::factory()->create();
    UserCourse::create([
        'user_id' => $this->student->id,
        'course_id' => $course->id,
        'current_week' => 2,
        'current_day' => 1,
        'started_at' => now()->subWeeks(2),
        'time_spent_minutes' => 120,
    ]);

    $progress = $this->service->getCourseProgress($this->student, $this->start, $this->end);

    expect($progress)->toHaveCount(1);
    expect($progress[0])->toHaveKeys(['title', 'progress', 'started_at', 'time_spent_minutes', 'learning_objectives']);
    expect($progress[0]['title'])->toBe($course->title);
});

it('returns assessment results with trivia scores', function () {
    $course = Course::factory()->create();
    UserCourse::create([
        'user_id' => $this->student->id,
        'course_id' => $course->id,
        'current_week' => 3,
        'current_day' => 1,
        'started_at' => now()->subWeeks(3),
        'trivia_scores' => [
            'week_1' => ['score' => 80, 'recorded_at' => now()->toIso8601String()],
            'week_2' => ['score' => 90, 'recorded_at' => now()->toIso8601String()],
        ],
    ]);

    $results = $this->service->getAssessmentResults($this->student, $this->start, $this->end);

    expect($results)->toHaveCount(1);
    expect($results[0]['average'])->toBe(85.0);
    expect($results[0]['course_title'])->toBe($course->title);
});

it('returns interaction log from prompt questions', function () {
    $question = PromptQuestion::withoutEvents(function () {
        return $this->student->promptQuestions()->create([
            'question' => 'What is photosynthesis?',
            'total_tokens' => 100,
        ]);
    });

    $question->promptAnswer()->create([
        'content' => 'Photosynthesis is the process...',
        'word_count' => 150,
    ]);

    $log = $this->service->getInteractionLog($this->student, $this->start, $this->end);

    expect($log)->toHaveCount(1);
    expect($log[0]['question'])->toBe('What is photosynthesis?');
    expect($log[0]['word_count'])->toBe(150);
});

it('calculates summary statistics correctly', function () {
    ActiveTime::create([
        'user_id' => $this->student->id,
        'date' => now()->subDays(5)->toDateString(),
        'total_seconds' => 3600,
    ]);

    ActiveTime::create([
        'user_id' => $this->student->id,
        'date' => now()->subDays(3)->toDateString(),
        'total_seconds' => 7200,
    ]);

    $course = Course::factory()->create();
    UserCourse::create([
        'user_id' => $this->student->id,
        'course_id' => $course->id,
        'current_week' => 1,
        'current_day' => 1,
        'started_at' => now()->subWeeks(1),
    ]);

    PromptQuestion::withoutEvents(function () {
        $this->student->promptQuestions()->create([
            'question' => 'Test question',
            'total_tokens' => 50,
        ]);
    });

    $stats = $this->service->getSummaryStatistics($this->student, $this->start, $this->end);

    expect($stats['total_instruction_days'])->toBe(2);
    expect($stats['total_instruction_hours'])->toBe(3.0);
    expect($stats['courses_active'])->toBe(1);
    expect($stats['courses_completed'])->toBe(0);
    expect($stats['total_interactions'])->toBe(1);
});

it('returns empty arrays when no data exists', function () {
    $log = $this->service->getDailyActivityLog($this->student, $this->start, $this->end);
    $progress = $this->service->getCourseProgress($this->student, $this->start, $this->end);
    $results = $this->service->getAssessmentResults($this->student, $this->start, $this->end);
    $interactions = $this->service->getInteractionLog($this->student, $this->start, $this->end);

    expect($log)->toBeArray()->toBeEmpty();
    expect($progress)->toBeArray()->toBeEmpty();
    expect($results)->toBeArray()->toBeEmpty();
    expect($interactions)->toBeArray()->toBeEmpty();
});
