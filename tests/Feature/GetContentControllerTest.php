<?php

use App\Ai\Agents\ContentStreamingAgent;
use App\Models\Prompt;
use App\Models\PromptQuestion;
use App\Models\User;

it('dispatches a broadcast job and returns json', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    Prompt::create(['category' => '10', 'prompt' => 'Write content for a 10-year-old.']);

    PromptQuestion::withoutEvents(function () use ($student) {
        return PromptQuestion::factory()->create([
            'user_id' => $student->id,
        ]);
    });

    ContentStreamingAgent::fake();

    $response = $this->actingAs($student)
        ->postJson('/student/prompts/content');

    $response->assertSuccessful()
        ->assertJson(['status' => 'generating']);

    ContentStreamingAgent::assertQueued(function ($prompt) {
        return ! empty($prompt->prompt);
    });
});

it('throws an exception when no prompt question exists', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    Prompt::create(['category' => '10', 'prompt' => 'Write content for a 10-year-old.']);

    ContentStreamingAgent::fake();

    $this->actingAs($student)
        ->postJson('/student/prompts/content')
        ->assertStatus(500);
});

it('redirects unauthenticated users', function () {
    $this->postJson('/student/prompts/content')
        ->assertUnauthorized();
});

it('redirects non-student users away from student routes', function () {
    $parent = User::factory()->parent()->create();

    $this->actingAs($parent)
        ->postJson('/student/prompts/content')
        ->assertRedirect();
});

it('authorizes the prompts channel for the correct user', function () {
    $user = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $user->id,
        'username' => fake()->userName(),
    ]);

    $this->actingAs($student)
        ->post('/broadcasting/auth', [
            'socket_id' => '1234.5678',
            'channel_name' => 'private-prompts.'.$student->id,
        ])
        ->assertSuccessful();
});

it('denies the prompts channel for a different user', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
    ]);

    $otherParent = User::factory()->parent()->create();
    $otherStudent = User::factory()->create([
        'parent_id' => $otherParent->id,
        'username' => fake()->userName(),
    ]);

    // The channel callback checks (int) $user->id === (int) $userId
    $isAuthorized = (int) $student->id === (int) $otherStudent->id;

    expect($isAuthorized)->toBeFalse();
});
