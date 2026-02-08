<?php

use App\Ai\Agents\SingleCourseWeekGenerationAgent;
use App\Events\CourseWeeksGenerated;
use App\Jobs\GenerateCourseWeek;
use App\Models\Course;
use App\Models\CourseDay;
use App\Models\CoursePrompt;
use App\Models\User;
use Illuminate\Support\Facades\Bus;

it('generates a single week and days from agent response', function () {
    SingleCourseWeekGenerationAgent::fake(fn () => [
        'week_number' => 1,
        'title' => 'Introduction to Science',
        'description' => 'An introductory week covering basics.',
        'learning_objectives' => ['Understand basics', 'Explore topics'],
        'days' => [
            [
                'day_number' => 1,
                'title' => 'What is Science?',
                'description' => 'Day one introduction.',
                'learning_objectives' => ['Define science'],
                'estimated_duration_minutes' => 15,
            ],
            [
                'day_number' => 2,
                'title' => 'Scientific Method',
                'description' => 'Learning the method.',
                'learning_objectives' => ['Understand steps'],
                'estimated_duration_minutes' => 15,
            ],
        ],
    ]);

    $course = Course::factory()->elementary()->create(['length_in_weeks' => 1]);

    (new GenerateCourseWeek($course, 1, 2, 1))->handle();

    expect($course->coursePrompts()->count())->toBe(1);

    $week = $course->coursePrompts()->first();

    expect($week->title)->toBe('Introduction to Science');
    expect($week->week_number)->toBe(1);
    expect($week->days()->count())->toBe(2);
    expect($week->days()->where('day_number', 1)->first()->title)->toBe('What is Science?');
});

it('throws exception when agent fails so batch can catch it', function () {
    SingleCourseWeekGenerationAgent::fake(function () {
        throw new \Exception('AI service unavailable');
    });

    $course = Course::factory()->create(['length_in_weeks' => 1]);

    (new GenerateCourseWeek($course, 1, 5, 1))->handle();
})->throws(\Exception::class, 'AI service unavailable');

it('dispatches a batch of jobs via the generate weeks endpoint', function () {
    Bus::fake([GenerateCourseWeek::class]);

    $admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);
    config(['app.admin_emails' => [$admin->email]]);

    $course = Course::factory()->create(['length_in_weeks' => 3]);

    $response = $this
        ->actingAs($admin)
        ->postJson("/admin/courses/{$course->id}/generate-weeks", [
            'days_per_week' => 3,
        ]);

    $response->assertSuccessful();
    $response->assertJson([
        'success' => true,
        'message' => 'Week generation has been queued. You will be notified when it completes.',
    ]);

    Bus::assertBatched(function ($batch) {
        return $batch->jobs->count() === 3
            && $batch->jobs->every(fn ($job) => $job instanceof GenerateCourseWeek);
    });
});

it('deletes existing weeks and days before dispatching batch', function () {
    Bus::fake([GenerateCourseWeek::class]);

    $admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);
    config(['app.admin_emails' => [$admin->email]]);

    $course = Course::factory()->create(['length_in_weeks' => 1]);
    $oldWeek = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    CourseDay::factory()->create(['course_prompt_id' => $oldWeek->id, 'day_number' => 1]);
    CourseDay::factory()->create(['course_prompt_id' => $oldWeek->id, 'day_number' => 2]);

    $this
        ->actingAs($admin)
        ->postJson("/admin/courses/{$course->id}/generate-weeks");

    expect($course->coursePrompts()->count())->toBe(0);
    expect(CoursePrompt::find($oldWeek->id))->toBeNull();
});

it('broadcasts CourseWeeksGenerated on the correct private channel', function () {
    $event = new CourseWeeksGenerated(
        courseId: 5,
        status: 'completed',
        message: 'Successfully generated 4 weeks with 20 days',
    );

    $channels = $event->broadcastOn();

    expect($channels)->toHaveCount(1);
    expect($channels[0]->name)->toBe('private-courses.5');
});

it('broadcasts correct payload for CourseWeeksGenerated', function () {
    $event = new CourseWeeksGenerated(
        courseId: 7,
        status: 'failed',
        message: 'Something went wrong',
    );

    $data = $event->broadcastWith();

    expect($data)->toBe([
        'course_id' => 7,
        'status' => 'failed',
        'message' => 'Something went wrong',
    ]);
});
