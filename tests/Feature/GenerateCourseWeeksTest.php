<?php

use App\Ai\Agents\SingleCourseWeekGenerationAgent;
use App\Enums\ContentStatus;
use App\Events\CourseWeekCreated;
use App\Events\CourseWeeksGenerated;
use App\Jobs\GenerateCourseWeek;
use App\Models\Course;
use App\Models\CourseDay;
use App\Models\CoursePrompt;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

it('generates a single week and days from agent response', function () {
    Event::fake([CourseWeekCreated::class, CourseWeeksGenerated::class]);

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

    $course->refresh();
    expect($course->generation_status)->toBe(ContentStatus::Completed);

    Event::assertDispatched(CourseWeekCreated::class, function ($event) use ($course) {
        return $event->courseId === $course->id
            && $event->weekData['title'] === 'Introduction to Science';
    });

    Event::assertDispatched(CourseWeeksGenerated::class, function ($event) use ($course) {
        return $event->courseId === $course->id && $event->status === 'completed';
    });
});

it('throws exception when agent fails and sets generation status to failed', function () {
    Event::fake([CourseWeeksGenerated::class]);

    SingleCourseWeekGenerationAgent::fake(function () {
        throw new \Exception('AI service unavailable');
    });

    $course = Course::factory()->create(['length_in_weeks' => 1]);

    try {
        (new GenerateCourseWeek($course, 1, 5, 1))->handle();
    } catch (\Exception $e) {
        // Expected
    }

    $course->refresh();
    expect($course->generation_status)->toBe(ContentStatus::Failed);

    Event::assertDispatched(CourseWeeksGenerated::class, function ($event) use ($course) {
        return $event->courseId === $course->id && $event->status === 'failed';
    });
});

it('dispatches a single job via the generate weeks endpoint', function () {
    Queue::fake([GenerateCourseWeek::class]);

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

    Queue::assertPushed(GenerateCourseWeek::class, 1);
    Queue::assertPushed(GenerateCourseWeek::class, function ($job) {
        return $job->weekNumber === 1
            && $job->daysPerWeek === 3
            && $job->totalWeeks === 3;
    });

    $course->refresh();
    expect($course->generation_status)->toBe(ContentStatus::Generating);
});

it('deletes existing weeks and days before dispatching job', function () {
    Queue::fake([GenerateCourseWeek::class]);

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

it('includes previously generated weeks in the AI prompt', function () {
    Event::fake([CourseWeekCreated::class, CourseWeeksGenerated::class]);

    $course = Course::factory()->create(['length_in_weeks' => 2]);

    // Create an existing week (simulating week 1 already generated)
    $existingWeek = CoursePrompt::factory()->create([
        'course_id' => $course->id,
        'week_number' => 1,
        'title' => 'Introduction to Basics',
    ]);
    CourseDay::factory()->create([
        'course_prompt_id' => $existingWeek->id,
        'day_number' => 1,
        'title' => 'Getting Started',
    ]);

    $promptReceived = null;
    SingleCourseWeekGenerationAgent::fake(function ($prompt) use (&$promptReceived) {
        $promptReceived = $prompt;

        return [
            'title' => 'Advanced Topics',
            'description' => 'Building on previous knowledge.',
            'learning_objectives' => ['Go deeper'],
            'days' => [
                [
                    'day_number' => 1,
                    'title' => 'Deep Dive',
                    'description' => 'Advanced content.',
                    'learning_objectives' => ['Master concepts'],
                    'estimated_duration_minutes' => 15,
                ],
            ],
        ];
    });

    (new GenerateCourseWeek($course, 2, 1, 2))->handle();

    expect($promptReceived)->toContain('PREVIOUSLY GENERATED WEEKS');
    expect($promptReceived)->toContain('Introduction to Basics');
    expect($promptReceived)->toContain('Getting Started');
});

it('dispatches next week job after completing current week', function () {
    Queue::fake([GenerateCourseWeek::class]);
    Event::fake([CourseWeekCreated::class]);

    SingleCourseWeekGenerationAgent::fake(fn () => [
        'title' => 'Week One',
        'description' => 'First week.',
        'learning_objectives' => ['Learn'],
        'days' => [
            [
                'day_number' => 1,
                'title' => 'Day 1',
                'description' => 'First day.',
                'learning_objectives' => ['Start'],
                'estimated_duration_minutes' => 15,
            ],
        ],
    ]);

    $course = Course::factory()->create(['length_in_weeks' => 3]);

    (new GenerateCourseWeek($course, 1, 1, 3))->handle();

    Queue::assertPushed(GenerateCourseWeek::class, function ($job) {
        return $job->weekNumber === 2 && $job->totalWeeks === 3;
    });
});

it('does not dispatch next week job on last week and sets completed', function () {
    Queue::fake([GenerateCourseWeek::class]);
    Event::fake([CourseWeekCreated::class, CourseWeeksGenerated::class]);

    SingleCourseWeekGenerationAgent::fake(fn () => [
        'title' => 'Final Week',
        'description' => 'Last week.',
        'learning_objectives' => ['Wrap up'],
        'days' => [
            [
                'day_number' => 1,
                'title' => 'Last Day',
                'description' => 'Final day.',
                'learning_objectives' => ['Finish'],
                'estimated_duration_minutes' => 15,
            ],
        ],
    ]);

    $course = Course::factory()->create(['length_in_weeks' => 2]);

    (new GenerateCourseWeek($course, 2, 1, 2))->handle();

    Queue::assertNotPushed(GenerateCourseWeek::class);

    $course->refresh();
    expect($course->generation_status)->toBe(ContentStatus::Completed);

    Event::assertDispatched(CourseWeeksGenerated::class, function ($event) use ($course) {
        return $event->courseId === $course->id && $event->status === 'completed';
    });
});

it('broadcasts CourseWeekCreated after creating each week', function () {
    Event::fake([CourseWeekCreated::class, CourseWeeksGenerated::class]);

    SingleCourseWeekGenerationAgent::fake(fn () => [
        'title' => 'Test Week',
        'description' => 'A test week.',
        'learning_objectives' => ['Test'],
        'days' => [
            [
                'day_number' => 1,
                'title' => 'Test Day',
                'description' => 'A test day.',
                'learning_objectives' => ['Test'],
                'estimated_duration_minutes' => 15,
            ],
        ],
    ]);

    $course = Course::factory()->create(['length_in_weeks' => 1]);

    (new GenerateCourseWeek($course, 1, 1, 1))->handle();

    Event::assertDispatched(CourseWeekCreated::class, function ($event) use ($course) {
        return $event->courseId === $course->id
            && $event->weekData['week_number'] === 1
            && $event->weekData['title'] === 'Test Week'
            && count($event->weekData['days']) === 1;
    });
});

it('broadcasts CourseWeekCreated on the correct private channel', function () {
    $event = new CourseWeekCreated(
        courseId: 42,
        weekData: ['id' => 1, 'week_number' => 1, 'title' => 'Test', 'description' => 'Desc', 'days_count' => 1, 'days' => []],
    );

    $channels = $event->broadcastOn();

    expect($channels)->toHaveCount(1);
    expect($channels[0]->name)->toBe('private-courses.42');
});

it('broadcasts correct payload for CourseWeekCreated', function () {
    $weekData = ['id' => 1, 'week_number' => 2, 'title' => 'Week 2', 'description' => 'Second week', 'days_count' => 3, 'days' => []];

    $event = new CourseWeekCreated(
        courseId: 10,
        weekData: $weekData,
    );

    $data = $event->broadcastWith();

    expect($data)->toBe([
        'course_id' => 10,
        'week' => $weekData,
    ]);
});

it('rejects request if generation is already in progress', function () {
    $admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);
    config(['app.admin_emails' => [$admin->email]]);

    $course = Course::factory()->generating()->create(['length_in_weeks' => 3]);

    $response = $this
        ->actingAs($admin)
        ->postJson("/admin/courses/{$course->id}/generate-weeks");

    $response->assertStatus(409);
    $response->assertJson([
        'success' => false,
        'error' => 'Week generation is already in progress.',
    ]);
});
