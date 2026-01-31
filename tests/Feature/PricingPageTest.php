<?php

use App\Models\User;

it('redirects /pricing to the landing page pricing section', function () {
    $response = $this->get('/pricing');

    $response->assertRedirect('/#pricing');
});

it('redirects /pricing for authenticated parents', function () {
    $parent = User::factory()->parent()->create();

    $response = $this
        ->actingAs($parent)
        ->get('/pricing');

    $response->assertRedirect('/#pricing');
});

it('redirects /pricing for authenticated students', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($student)
        ->get('/pricing');

    $response->assertRedirect('/#pricing');
});

it('includes plans and pricing props on the landing page', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Landing/Index')
        ->has('plans')
        ->has('pricing')
    );
});
