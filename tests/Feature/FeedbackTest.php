<?php

use App\Models\Feedback;
use App\Models\User;

it('allows user to view the feedback index page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/feedback');

    $response->assertOk();
});

it('allows user to view the feedback create page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/feedback/create');

    $response->assertOk();
});

it('allows user to view the feedback edit page', function () {
    $user = User::factory()->create();

    $feedback = Feedback::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->get("/feedback/$feedback->id/edit");

    $response->assertOk();
});

it('allows user to create a feedback', function () {
    $user = User::factory()->create();

    $title = fake()->sentence;
    $description = fake()->paragraph;

    $response = $this
        ->actingAs($user)
        ->post('/feedback', [
            'title' => $title,
            'description' => $description,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/feedback');

    $feedback = Feedback::where('title', $title)->where('description', $description)->count();

    expect($feedback)->toBe(1);
});

it('validates the users input when creating feedback', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/feedback');

    $response
        ->assertSessionHasErrors(['title', 'description']);
});

it('allows the user to update one of their feedback', function () {
    $user = User::factory()->create();

    $feedback = Feedback::factory()->create(['user_id' => $user->id]);

    $response = $this
        ->actingAs($user)
        ->patch("/feedback/$feedback->id", [
            'title' => 'Test Title',
            'description' => 'Test description',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/feedback');

    $feedback->refresh();

    expect($feedback->title)->toBe('Test Title');
    expect($feedback->description)->toBe('Test description');
});

it('does not allow users to update feedback thats not theirs', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $feedback = Feedback::factory()->create(['user_id' => $userTwo->id]);

    $response = $this
        ->actingAs($user)
        ->patch("/feedback/$feedback->id", [
            'title' => 'Test Feedback Title',
            'description' => 'Test Feedback Description',
        ]);

    $response->assertForbidden();
});

it('allows the user to delete feedback', function () {
    $user = User::factory()->create();

    $feedback = Feedback::factory()->create(['user_id' => $user->id]);

    $feedbackCount = Feedback::where('user_id', $user->id)->count();
    expect($feedbackCount)->toBe(1);

    $this
        ->actingAs($user)
        ->delete("/feedback/$feedback->id");

    $feedbackCount = Feedback::where('user_id', $user->id)->count();

    expect($feedbackCount)->toBe(0);
});

it('does not allow user to delete another users feedback', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $feedback = Feedback::factory()->create(['user_id' => $userTwo->id]);

    $response = $this
        ->actingAs($user)
        ->delete("/feedback/$feedback->id");

    $response->assertForbidden();
});
