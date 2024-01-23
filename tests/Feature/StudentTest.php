<?php

use App\Models\User;

use function Pest\Faker\fake;

it('allows parent to view the student index page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/parent/users');

    $response->assertOk();
});

it('allows parent to view the student create page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/parent/users/create');

    $response->assertOk();
});

it('allows parent to view the student edit page', function () {
    $user = User::factory()->create();

    $student = User::factory()->create([
        'parent_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/parent/users/'.$student->id.'/edit');

    $response->assertOk();
});

it('does not allow parent to view the student edit page for a student that is not theirs', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $student = User::factory()->create([
        'parent_id' => $userTwo->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/parent/users/'.$student->id.'/edit');

    $response->assertForbidden();
});

it('allows parent to view the student show page', function () {
    $user = User::factory()->create();

    $student = User::factory()->create([
        'parent_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/parent/users/'.$student->id);

    $response->assertOk();
});

it('does not allow parent to view the student show page for a student that is not theirs', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $student = User::factory()->create([
        'parent_id' => $userTwo->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/parent/users/'.$student->id);

    $response->assertForbidden();
});

it('allows parent to create a student', function () {
    $user = User::factory()->parent()->create();

    $username = fake()->userName;

    $response = $this
        ->actingAs($user)
        ->post('/parent/users', [
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
        ->assertRedirect('/parent/users');

    $student = User::where('username', $username)->count();

    expect($student)->toBe(1);
});

it('validates the parents input when creating a student', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/parent/users');

    $response
        ->assertSessionHasErrors(['name', 'username', 'password', 'grade', 'age', 'timezone']);
});

it('does not allow parent to create a student with a username that already exists', function () {
    $user = User::factory()->create();

    $student = User::factory()->create([
        'parent_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->post('/parent/users', [
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

    $student = User::factory()->create([
        'parent_id' => $user->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/parent/users/'.$student->id, [
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
        ->assertRedirect('/parent/users');

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

    $student = User::factory()->create(['parent_id' => $userTwo->id]);

    $response = $this
        ->actingAs($user)
        ->patch('/parent/users/'.$student->id, [
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

    $student = User::factory()->create(['parent_id' => $user->id]);

    $studentCount = User::where('parent_id', $user->id)->count();
    expect($studentCount)->toBe(1);

    $this
        ->actingAs($user)
        ->delete('/parent/users/'.$student->id);

    $studentCount = User::where('parent_id', $user->id)->count();

    expect($studentCount)->toBe(0);
});

it('does not allow parent to delete a student thats not theirs', function () {
    $user = User::factory()->create();
    $userTwo = User::factory()->create();

    $student = User::factory()->create(['parent_id' => $userTwo->id]);

    $response = $this
        ->actingAs($user)
        ->delete('/parent/users/'.$student->id);

    $response->assertForbidden();
});
