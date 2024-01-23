<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;

test('parent login screen can be rendered', function () {
    $response = $this->get('/parent/login');

    $response->assertStatus(200);
});

test('student login screen can be rendered', function () {
    $response = $this->get('/student/login');

    $response->assertStatus(200);
});

test('parents can authenticate using the login screen', function () {
    $user = User::factory()->parent()->create();

    $response = $this->post('/login', [
        'login' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('students can authenticate using the login screen', function () {
    $user = User::factory()->create(['username' => 'test_student']);

    $response = $this->post('/login', [
        'login' => $user->username,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
