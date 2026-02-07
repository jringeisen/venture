<?php

use App\Ai\Agents\CourseWeekGenerationAgent;
use App\Events\CourseWeeksGenerated;
use App\Jobs\GenerateCourseWeeks;
use App\Models\Course;
use App\Models\CourseDay;
use App\Models\CoursePrompt;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;

it('generates weeks and days from agent response and broadcasts completion', function () {
    Event::fake([CourseWeeksGenerated::class]);

    CourseWeekGenerationAgent::fake(fn () => [
        'weeks' => [
            [
                'week_number' => 1,
                'title' => 'Introduction to Science',
                'description' => 'An introductory week covering basics.',
                'learning_objectives' => ['Understand basics', 'Explore topics'],
                'estimated_duration_minutes' => 30,
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
            ],
        ],
    ]);

    $course = Course::factory()->elementary()->create(['length_in_weeks' => 1]);

    (new GenerateCourseWeeks($course, 2))->handle();

    expect($course->coursePrompts()->count())->toBe(1);

    $week = $course->coursePrompts()->first();

    expect($week->title)->toBe('Introduction to Science');
    expect($week->week_number)->toBe(1);
    expect($week->days()->count())->toBe(2);
    expect($week->days()->where('day_number', 1)->first()->title)->toBe('What is Science?');

    Event::assertDispatched(CourseWeeksGenerated::class, function ($event) use ($course) {
        return $event->courseId === $course->id && $event->status === 'completed';
    });
});

it('broadcasts failure when agent throws exception', function () {
    Event::fake([CourseWeeksGenerated::class]);

    CourseWeekGenerationAgent::fake(function () {
        throw new \Exception('AI service unavailable');
    });

    $course = Course::factory()->create(['length_in_weeks' => 1]);

    (new GenerateCourseWeeks($course, 5))->handle();

    Event::assertDispatched(CourseWeeksGenerated::class, function ($event) use ($course) {
        return $event->courseId === $course->id
            && $event->status === 'failed'
            && str_contains($event->message, 'AI service unavailable');
    });
});

it('deletes existing weeks and days before creating new ones', function () {
    Event::fake([CourseWeeksGenerated::class]);

    CourseWeekGenerationAgent::fake(fn () => [
        'weeks' => [
            [
                'week_number' => 1,
                'title' => 'New Week',
                'description' => 'Replacement week.',
                'learning_objectives' => ['Learn new things'],
                'estimated_duration_minutes' => 30,
                'days' => [
                    [
                        'day_number' => 1,
                        'title' => 'New Day',
                        'description' => 'Replacement day.',
                        'learning_objectives' => ['New objective'],
                        'estimated_duration_minutes' => 15,
                    ],
                ],
            ],
        ],
    ]);

    $course = Course::factory()->create(['length_in_weeks' => 1]);
    $oldWeek = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    CourseDay::factory()->create(['course_prompt_id' => $oldWeek->id, 'day_number' => 1]);
    CourseDay::factory()->create(['course_prompt_id' => $oldWeek->id, 'day_number' => 2]);

    (new GenerateCourseWeeks($course, 1))->handle();

    expect($course->coursePrompts()->count())->toBe(1);
    expect($course->coursePrompts()->first()->title)->toBe('New Week');
    expect(CoursePrompt::find($oldWeek->id))->toBeNull();
});

it('dispatches the job via the generate weeks endpoint', function () {
    Bus::fake([GenerateCourseWeeks::class]);

    $admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);
    config(['app.admin_emails' => [$admin->email]]);

    $course = Course::factory()->create();

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

    Bus::assertDispatched(GenerateCourseWeeks::class, function ($job) use ($course) {
        return $job->course->id === $course->id && $job->daysPerWeek === 3;
    });
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
