<?php

use App\Models\User;
use App\Models\Student;
use function Pest\Faker\fake;

it('allows parent to create a student', function () {
    $user = User::factory()->create();

    $username = fake()->userName;

    $response = $this
        ->actingAs($user)
        ->post('/students', [
            'name' => fake()->name,
            'username' => $username,
            'password' => 'password',
            'password_confirmation' => 'password',
            'grade' => fake()->numberBetween(1, 12),
            'age' => fake()->numberBetween(5, 18),
            'timezone' => fake()->timezone,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/students');

    $student = Student::where('username', $username)->count();

    expect($student)->toBe(1);
});

it('validates the parents input when creating a student', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/students');

    $response
        ->assertSessionHasErrors(['name', 'username', 'password', 'grade', 'age', 'timezone']);
});

it('allows the parent to delete a student', function () {
    $user = User::factory()->create();

    $student = Student::factory()->create([
        'user_id' => $user->id,
    ]);

    $studentCount = Student::count();
    expect($studentCount)->toBe(1);

    $this
        ->actingAs($user)
        ->delete('/students/' . $student->id);

    $studentCount = Student::count();

    expect($studentCount)->toBe(0);
});
