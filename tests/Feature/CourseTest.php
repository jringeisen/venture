<?php

use App\Models\Course;
use App\Models\CoursePrompt;
use App\Models\User;
use App\Models\UserCourse;

it('allows student to view the courses index page', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);

    $response = $this
        ->actingAs($student)
        ->get('/student/courses');

    $response->assertOk();
});

it('allows student to view a course detail page', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course = Course::factory()->create();

    $response = $this
        ->actingAs($student)
        ->get('/student/courses/'.$course->id);

    $response->assertOk();
});

it('allows student to enroll in a course', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course = Course::factory()->create();

    expect($student->isEnrolledInCourse($course))->toBeFalse();

    $response = $this
        ->actingAs($student)
        ->post('/student/courses/'.$course->id.'/enroll');

    $response->assertRedirect();

    $student->refresh();
    expect($student->isEnrolledInCourse($course))->toBeTrue();
});

it('prevents student from enrolling in a course twice', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course = Course::factory()->create();

    $student->enrollInCourse($course);

    $response = $this
        ->actingAs($student)
        ->post('/student/courses/'.$course->id.'/enroll');

    $response->assertSessionHasErrors();
});

it('allows student to view their enrolled courses page', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course = Course::factory()->create();

    $student->enrollInCourse($course);

    $response = $this
        ->actingAs($student)
        ->get('/student/courses/enrolled');

    $response->assertOk();
});

it('allows student to view course learning page when enrolled', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course = Course::factory()->create();
    CoursePrompt::factory()->create([
        'course_id' => $course->id,
        'week_number' => 1,
    ]);

    $student->enrollInCourse($course);

    $response = $this
        ->actingAs($student)
        ->get('/student/courses/'.$course->id.'/learn/1');

    $response->assertOk();
});

it('creates user course enrollment with correct data', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course = Course::factory()->create();

    $enrollment = $student->enrollInCourse($course);

    expect($enrollment)->toBeInstanceOf(UserCourse::class);
    expect($enrollment->user_id)->toBe($student->id);
    expect($enrollment->course_id)->toBe($course->id);
    expect($enrollment->started_at)->not->toBeNull();
    expect($enrollment->completed_at)->toBeNull();
});

it('returns correct enrollment status for student', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course1 = Course::factory()->create();
    $course2 = Course::factory()->create();

    $student->enrollInCourse($course1);

    expect($student->isEnrolledInCourse($course1))->toBeTrue();
    expect($student->isEnrolledInCourse($course2))->toBeFalse();
});

it('can search courses', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);

    Course::factory()->create(['title' => 'Introduction to Astronomy']);
    Course::factory()->create(['title' => 'World History']);

    $response = $this
        ->actingAs($student)
        ->get('/student/courses/search?query=astronomy');

    $response->assertOk();
    $response->assertJsonFragment(['title' => 'Introduction to Astronomy']);
});

it('validates search query length', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);

    $response = $this
        ->actingAs($student)
        ->get('/student/courses/search?query=a');

    $response->assertSessionHasErrors('query');
});

it('course has many course prompts', function () {
    $course = Course::factory()->create(['length_in_weeks' => 4]);

    CoursePrompt::factory()->count(4)->create([
        'course_id' => $course->id,
    ]);

    expect($course->coursePrompts)->toHaveCount(4);
});

it('course prompt belongs to course', function () {
    $course = Course::factory()->create();
    $prompt = CoursePrompt::factory()->create(['course_id' => $course->id]);

    expect($prompt->course->id)->toBe($course->id);
});

it('user course belongs to user and course', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course = Course::factory()->create();

    $enrollment = UserCourse::factory()->create([
        'user_id' => $student->id,
        'course_id' => $course->id,
    ]);

    expect($enrollment->user->id)->toBe($student->id);
    expect($enrollment->course->id)->toBe($course->id);
});

it('can mark user course as completed', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id]);
    $course = Course::factory()->create();

    $enrollment = $student->enrollInCourse($course);

    expect($enrollment->is_completed)->toBeFalse();

    $enrollment->markCompleted();

    expect($enrollment->is_completed)->toBeTrue();
    expect($enrollment->completed_at)->not->toBeNull();
});

it('course factory creates valid course', function () {
    $course = Course::factory()->create();

    expect($course->title)->not->toBeEmpty();
    expect($course->description)->not->toBeEmpty();
    expect($course->length_in_weeks)->toBeGreaterThan(0);
});

it('course prompt factory creates valid prompt', function () {
    $course = Course::factory()->create();
    $prompt = CoursePrompt::factory()->create(['course_id' => $course->id]);

    expect($prompt->title)->not->toBeEmpty();
    expect($prompt->week_number)->toBeGreaterThan(0);
    expect($prompt->course_id)->toBe($course->id);
});
