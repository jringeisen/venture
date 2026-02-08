<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->parent()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

// --- School year dates ---

test('can update school year dates', function () {
    $user = User::factory()->parent()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'school_year_start' => '2025-08-01',
            'school_year_end' => '2026-05-31',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    expect($user->school_year_start->toDateString())->toBe('2025-08-01');
    expect($user->school_year_end->toDateString())->toBe('2026-05-31');
});

test('validates school year end must be after start', function () {
    $user = User::factory()->parent()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->patch('/profile', [
            'school_year_start' => '2026-05-31',
            'school_year_end' => '2025-08-01',
        ]);

    $response->assertSessionHasErrors('school_year_end');
});

test('requires both dates or neither', function () {
    $user = User::factory()->parent()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->patch('/profile', [
            'school_year_start' => '2025-08-01',
            'school_year_end' => null,
        ]);

    $response->assertSessionHasErrors('school_year_end');
});
