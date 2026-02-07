<?php

use App\Ai\Agents\ModerationAgent;
use App\Models\ComplianceReport;
use App\Models\Course;
use App\Models\DailyQuestionCount;
use App\Models\Prompt;
use App\Models\User;

beforeEach(function () {
    config([
        'subscription.plans.explorer.stripe_monthly_price' => 'price_explorer_monthly_test',
        'subscription.plans.explorer.stripe_yearly_price' => 'price_explorer_yearly_test',
        'subscription.plans.family.stripe_monthly_price' => 'price_family_monthly_test',
        'subscription.plans.family.stripe_yearly_price' => 'price_family_yearly_test',
    ]);
});

it('blocks AI question at free tier limit', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    DailyQuestionCount::create([
        'user_id' => $student->id,
        'parent_id' => $parent->id,
        'date' => now()->toDateString(),
        'count' => 5,
    ]);

    $response = $this
        ->actingAs($student)
        ->post('/student/prompts', [
            'question' => 'What is the meaning of life?',
        ]);

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Student/Prompts/Index')
        ->where('result.flagged', true)
        ->where('result.upgrade_feature', 'ai_questions')
    );
});

it('allows AI question when under free tier limit', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    Prompt::unguarded(fn () => Prompt::updateOrCreate(['category' => 'moderation'], ['prompt' => 'Test moderation prompt']));

    ModerationAgent::fake(fn () => ['flagged' => false, 'message' => null]);

    $response = $this
        ->actingAs($student)
        ->post('/student/prompts', [
            'question' => 'What is the meaning of life?',
        ]);

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Student/Prompts/Index')
        ->where('result.flagged', false)
    );
});

it('blocks course enrollment when at free tier limit', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $course1 = Course::factory()->create();
    $student->enrollInCourse($course1);

    $course2 = Course::factory()->create();

    $response = $this
        ->actingAs($student)
        ->post('/student/courses/'.$course2->id.'/enroll');

    $response->assertSessionHasErrors('enrollment');
});

it('allows course enrollment on family plan', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $course1 = Course::factory()->create();
    $student->enrollInCourse($course1);

    $course2 = Course::factory()->create();

    $response = $this
        ->actingAs($student)
        ->post('/student/courses/'.$course2->id.'/enroll');

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

it('blocks compliance report generation on free tier', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $student->id,
            'state' => 'FL',
            'title' => 'Test Report',
        ]);

    $response->assertSessionHasErrors('limit');
});

it('allows compliance report generation on family plan', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $student->id,
            'state' => 'FL',
            'title' => 'Test Report',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
});

it('blocks PDF download on free tier', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $report = ComplianceReport::factory()->forParentAndStudent($parent, $student)->create();

    $response = $this
        ->actingAs($parent)
        ->get("/parent/compliance/reports/{$report->id}/pdf");

    $response->assertForbidden();
});

it('blocks student creation when at free tier limit', function () {
    $parent = User::factory()->parent()->create();

    for ($i = 0; $i < 3; $i++) {
        User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->unique()->userName(), 'grade' => 5, 'age' => 10]);
    }

    $response = $this
        ->actingAs($parent)
        ->post('/parent/users', [
            'name' => 'New Student',
            'username' => 'newstudent_'.fake()->randomNumber(5),
            'password' => 'password',
            'password_confirmation' => 'password',
            'grade' => 5,
            'age' => 10,
            'timezone' => 'America/New_York',
        ]);

    $response->assertSessionHasErrors('limit');
});

it('allows student creation on family plan', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();

    for ($i = 0; $i < 3; $i++) {
        User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->unique()->userName(), 'grade' => 5, 'age' => 10]);
    }

    $username = 'newstudent_'.fake()->randomNumber(5);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/users', [
            'name' => 'New Student',
            'username' => $username,
            'password' => 'password',
            'password_confirmation' => 'password',
            'grade' => 5,
            'age' => 10,
            'timezone' => 'America/New_York',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/parent/users');
});

it('blocks certificate access on free tier', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $course = Course::factory()->create();
    $enrollment = $student->enrollInCourse($course);
    $enrollment->markCompleted();

    $response = $this
        ->actingAs($student)
        ->get('/student/courses/'.$course->id.'/certificate');

    $response->assertRedirect();
    $response->assertSessionHasErrors('error');
});

it('allows all features on family plan', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    Prompt::unguarded(fn () => Prompt::updateOrCreate(['category' => 'moderation'], ['prompt' => 'Test moderation prompt']));

    ModerationAgent::fake(fn () => ['flagged' => false, 'message' => null]);

    // Can ask questions with high usage
    DailyQuestionCount::create([
        'user_id' => $student->id,
        'parent_id' => $parent->id,
        'date' => now()->toDateString(),
        'count' => 50,
    ]);

    $response = $this
        ->actingAs($student)
        ->post('/student/prompts', [
            'question' => 'Another question?',
        ]);

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->where('result.flagged', false)
    );
});
