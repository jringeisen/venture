<?php

use App\Models\Student;
use App\Models\User;

use function Pest\Faker\fake;

it('allows parent to view the student index page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/students');

    $response->assertOk();
});

it('allows parent to view the student create page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/students/create');

    $response->assertOk();
});

it('allows parent to view the student edit page', function () {
    $user = User::factory()->create();

    $student = Student::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/students/'.$student->id.'/edit');

    $response->assertOk();
});

it('does not allow parent to view the student edit page for a student that is not theirs', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $student = Student::factory()->create([
        'user_id' => $userTwo->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/students/'.$student->id.'/edit');

    $response->assertForbidden();
});

it('allows parent to view the student show page', function () {
    $user = User::factory()->create();

    $student = Student::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/students/'.$student->id);

    $response->assertOk();
});

it('does not allow parent to view the student show page for a student that is not theirs', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $student = Student::factory()->create([
        'user_id' => $userTwo->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/students/'.$student->id);

    $response->assertForbidden();
});

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

it('does not allow parent to create a student with a username that already exists', function () {
    $user = User::factory()->create();

    $student = Student::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->post('/students', [
            'name' => fake()->name,
            'username' => $student->username,
            'password' => 'password',
            'password_confirmation' => 'password',
            'grade' => fake()->numberBetween(1, 12),
            'age' => fake()->numberBetween(5, 18),
            'timezone' => fake()->timezone,
        ]);

    $response->assertSessionHasErrors('username');
});

it('allows the parent to update one of their students', function () {
    $user = User::factory()->create();

    $student = Student::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/students/'.$student->id, [
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => 'password',
            'password_confirmation' => 'password',
            'grade' => 5,
            'age' => 10,
            'timezone' => 'America/New_York',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/students');

    $student->refresh();

    expect($student->name)->toBe('Test User');
    expect($student->username)->toBe('testuser');
    expect($student->grade)->toBe(5);
    expect($student->age)->toBe(10);
    expect($student->timezone)->toBe('America/New_York');
});

it('does not allow parent to update a student thats not theirs', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $student = Student::factory()->create(['user_id' => $userTwo->id]);

    $response = $this
        ->actingAs($user)
        ->patch('/students/'.$student->id, [
            'name' => 'Test User',
            'username' => 'testuser',
            'password' => 'password',
            'password_confirmation' => 'password',
            'grade' => 5,
            'age' => 10,
            'timezone' => 'America/New_York',
        ]);

    $response->assertForbidden();
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
        ->delete('/students/'.$student->id);

    $studentCount = Student::count();

    expect($studentCount)->toBe(0);
});

it('does not allow parent to delete a student thats not theirs', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $student = Student::factory()->create(['user_id' => $userTwo->id]);

    $response = $this
        ->actingAs($user)
        ->delete('/students/'.$student->id);

    $response->assertForbidden();
});
