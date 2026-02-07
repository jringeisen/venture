<?php

use App\Models\ActiveTime;
use App\Models\Course;
use App\Models\DailyQuestionCount;
use App\Models\User;

beforeEach(function () {
    config([
        'subscription.plans.classroom.stripe_monthly_price' => 'price_classroom_monthly_test',
        'subscription.plans.classroom.stripe_yearly_price' => 'price_classroom_yearly_test',
        'subscription.plans.family.stripe_monthly_price' => 'price_family_monthly_test',
        'subscription.plans.family.stripe_yearly_price' => 'price_family_yearly_test',
    ]);
});

it('renders the dashboard page for a parent', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertSuccessful();
});

it('returns the correct Inertia component', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertInertia(fn ($page) => $page->component('Teachers/Dashboard'));
});

it('returns all three data props', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->has('familyStats')
        ->has('studentCards')
        ->has('recentActivity')
    );
});

it('includes weekly learning hours in family stats', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    ActiveTime::create([
        'user_id' => $student->id,
        'date' => now($parent->timezone)->toDateString(),
        'total_seconds' => 3600,
    ]);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->where('familyStats.learning_hours_this_week', '1h 0m')
    );
});

it('includes today question count in family stats', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    DailyQuestionCount::create([
        'user_id' => $student->id,
        'parent_id' => $parent->id,
        'date' => now($parent->timezone)->toDateString(),
        'count' => 7,
    ]);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->where('familyStats.questions_today', 7)
    );
});

it('includes active course count in family stats', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    $course = Course::factory()->create();
    $student->enrollInCourse($course);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->where('familyStats.active_courses', 1)
    );
});

it('returns student cards matching number of students', function () {
    $parent = User::factory()->parent()->create();

    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->unique()->userName(), 'grade' => 5, 'age' => 10]);
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->unique()->userName(), 'grade' => 7, 'age' => 12]);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->has('studentCards', 2)
    );
});

it('redirects to onboarding when parent has no students', function () {
    $parent = User::factory()->parent()->create();

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertRedirect(route('parent.users.create', ['status' => 'onboarding']));
});

it('includes enrollment records in activity feed', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    $course = Course::factory()->create(['title' => 'Biology 101']);
    $student->enrollInCourse($course);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->has('recentActivity', 1)
        ->where('recentActivity.0.type', 'enrollment')
    );
});

it('does not show other parents data', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $otherParent = User::factory()->parent()->create();
    $otherStudent = User::factory()->create([
        'parent_id' => $otherParent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    ActiveTime::create([
        'user_id' => $otherStudent->id,
        'date' => now()->toDateString(),
        'total_seconds' => 7200,
    ]);

    $response = $this->actingAs($parent)->get('/parent/dashboard');

    $response->assertInertia(fn ($page) => $page
        ->where('familyStats.learning_hours_this_week', '0h 0m')
        ->has('studentCards', 1)
    );
});

it('redirects students who try to access parent dashboard', function () {
    $student = User::factory()->student()->create();

    $response = $this->actingAs($student)->get('/parent/dashboard');

    $response->assertRedirect();
});

it('redirects unauthenticated users to login', function () {
    $response = $this->get('/parent/dashboard');

    $response->assertRedirect();
});
