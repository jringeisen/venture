<?php

use App\Ai\Agents\LessonContentGenerationAgent;
use App\Enums\ContentStatus;
use App\Events\DayContentGenerated;
use App\Jobs\GenerateDayContent;
use App\Models\Course;
use App\Models\CourseDay;
use App\Models\CoursePrompt;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;

it('generates content for a day and updates status', function () {
    Event::fake([DayContentGenerated::class]);

    LessonContentGenerationAgent::fake(fn () => [
        'content' => '<h2>Test Lesson Content</h2><p>This is a test lesson.</p>',
        'trivia_questions' => [
            [
                'question' => 'What is the test?',
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_answer' => 0,
                'difficulty' => 'easy',
            ],
        ],
    ]);

    $course = Course::factory()->elementary()->create();
    $week = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    $day = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 1,
        'content_status' => ContentStatus::Pending,
    ]);

    (new GenerateDayContent($day))->handle();

    $day->refresh();

    expect($day->content_status)->toBe(ContentStatus::Completed);
    expect($day->content)->toContain('Test Lesson Content');
    expect($day->trivia_questions)->toBeArray()->not->toBeEmpty();

    Event::assertDispatched(DayContentGenerated::class, function ($event) use ($day, $course, $week) {
        return $event->dayId === $day->id
            && $event->courseId === $course->id
            && $event->weekId === $week->id
            && $event->status === 'completed';
    });
});

it('sets status to failed when agent throws exception', function () {
    Event::fake([DayContentGenerated::class]);

    LessonContentGenerationAgent::fake(function () {
        throw new \Exception('AI service unavailable');
    });

    $course = Course::factory()->create();
    $week = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    $day = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 1,
        'content_status' => ContentStatus::Pending,
    ]);

    (new GenerateDayContent($day))->handle();

    $day->refresh();

    expect($day->content_status)->toBe(ContentStatus::Failed);

    Event::assertDispatched(DayContentGenerated::class, function ($event) use ($day) {
        return $event->dayId === $day->id && $event->status === 'failed';
    });
});

it('dispatches jobs for all days via generate all content endpoint', function () {
    Bus::fake([GenerateDayContent::class]);

    $admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);

    config(['app.admin_emails' => [$admin->email]]);

    $course = Course::factory()->create();
    $week1 = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    $week2 = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 2]);

    CourseDay::factory()->create(['course_prompt_id' => $week1->id, 'day_number' => 1]);
    CourseDay::factory()->create(['course_prompt_id' => $week1->id, 'day_number' => 2]);
    CourseDay::factory()->create(['course_prompt_id' => $week2->id, 'day_number' => 1]);

    $response = $this
        ->actingAs($admin)
        ->postJson("/admin/courses/{$course->id}/generate-all-content");

    $response->assertSuccessful();
    $response->assertJson([
        'success' => true,
        'total' => 3,
    ]);

    Bus::assertDispatched(GenerateDayContent::class, 3);

    $course->refresh();
    expect($course->content_generation_status)->toBe(ContentStatus::Generating);
});

it('returns error when no days exist for generate all content', function () {
    $admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);

    config(['app.admin_emails' => [$admin->email]]);

    $course = Course::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->postJson("/admin/courses/{$course->id}/generate-all-content");

    $response->assertStatus(422);
    $response->assertJson(['success' => false]);
});

it('returns content generation progress', function () {
    $admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);

    config(['app.admin_emails' => [$admin->email]]);

    $course = Course::factory()->create();
    $week = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);

    CourseDay::factory()->create(['course_prompt_id' => $week->id, 'day_number' => 1, 'content_status' => ContentStatus::Completed]);
    CourseDay::factory()->create(['course_prompt_id' => $week->id, 'day_number' => 2, 'content_status' => ContentStatus::Generating]);
    CourseDay::factory()->create(['course_prompt_id' => $week->id, 'day_number' => 3, 'content_status' => ContentStatus::Pending]);

    $response = $this
        ->actingAs($admin)
        ->getJson("/admin/courses/{$course->id}/content-progress");

    $response->assertSuccessful();
    $response->assertJson([
        'total' => 3,
        'completed' => 1,
        'generating' => 1,
        'pending' => 1,
        'failed' => 0,
    ]);
});

it('broadcasts on the correct private channel', function () {
    $event = new DayContentGenerated(
        courseId: 1,
        weekId: 2,
        dayId: 3,
        status: 'completed',
        dayNumber: 1,
        weekNumber: 1,
    );

    $channels = $event->broadcastOn();

    expect($channels)->toHaveCount(1);
    expect($channels[0]->name)->toBe('private-courses.1');
});

it('dispatches on the content-generation queue', function () {
    $course = Course::factory()->create();
    $week = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    $day = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 1,
    ]);

    $job = new GenerateDayContent($day);

    expect($job->queue)->toBe('content-generation');
});

it('broadcasts correct payload', function () {
    $event = new DayContentGenerated(
        courseId: 10,
        weekId: 20,
        dayId: 30,
        status: 'failed',
        dayNumber: 3,
        weekNumber: 2,
    );

    $data = $event->broadcastWith();

    expect($data)->toBe([
        'course_id' => 10,
        'week_id' => 20,
        'day_id' => 30,
        'status' => 'failed',
        'day_number' => 3,
        'week_number' => 2,
    ]);
});

it('rejects generate all content if already in progress', function () {
    Bus::fake([GenerateDayContent::class]);

    $admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);
    config(['app.admin_emails' => [$admin->email]]);

    $course = Course::factory()->generatingContent()->create();
    $week = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    CourseDay::factory()->create(['course_prompt_id' => $week->id, 'day_number' => 1]);

    $response = $this
        ->actingAs($admin)
        ->postJson("/admin/courses/{$course->id}/generate-all-content");

    $response->assertStatus(409);
    $response->assertJson([
        'success' => false,
        'error' => 'Content generation is already in progress.',
    ]);

    Bus::assertNotDispatched(GenerateDayContent::class);
});

it('sets content_generation_status to completed when all days finish', function () {
    Event::fake([DayContentGenerated::class]);

    LessonContentGenerationAgent::fake(fn () => [
        'content' => '<h2>Lesson</h2><p>Content.</p>',
        'trivia_questions' => [],
    ]);

    $course = Course::factory()->create(['content_generation_status' => ContentStatus::Generating]);
    $week = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    $day1 = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 1,
        'content_status' => ContentStatus::Completed,
    ]);
    $day2 = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 2,
        'content_status' => ContentStatus::Pending,
    ]);

    (new GenerateDayContent($day2))->handle();

    $course->refresh();
    expect($course->content_generation_status)->toBe(ContentStatus::Completed);
});

it('sets content_generation_status to failed when any day fails', function () {
    Event::fake([DayContentGenerated::class]);

    LessonContentGenerationAgent::fake(function () {
        throw new \Exception('AI service unavailable');
    });

    $course = Course::factory()->create(['content_generation_status' => ContentStatus::Generating]);
    $week = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    $day1 = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 1,
        'content_status' => ContentStatus::Completed,
    ]);
    $day2 = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 2,
        'content_status' => ContentStatus::Pending,
    ]);

    (new GenerateDayContent($day2))->handle();

    $course->refresh();
    expect($course->content_generation_status)->toBe(ContentStatus::Failed);
});

it('does not update course status when other days still pending', function () {
    Event::fake([DayContentGenerated::class]);

    LessonContentGenerationAgent::fake(fn () => [
        'content' => '<h2>Lesson</h2><p>Content.</p>',
        'trivia_questions' => [],
    ]);

    $course = Course::factory()->create(['content_generation_status' => ContentStatus::Generating]);
    $week = CoursePrompt::factory()->create(['course_id' => $course->id, 'week_number' => 1]);
    $day1 = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 1,
        'content_status' => ContentStatus::Pending,
    ]);
    $day2 = CourseDay::factory()->create([
        'course_prompt_id' => $week->id,
        'day_number' => 2,
        'content_status' => ContentStatus::Pending,
    ]);

    (new GenerateDayContent($day1))->handle();

    $course->refresh();
    expect($course->content_generation_status)->toBe(ContentStatus::Generating);
});
