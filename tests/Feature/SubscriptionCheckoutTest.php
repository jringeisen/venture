<?php

use App\Models\User;

beforeEach(function () {
    config([
        'subscription.plans.explorer.stripe_monthly_price' => 'price_explorer_monthly_test',
        'subscription.plans.explorer.stripe_yearly_price' => 'price_explorer_yearly_test',
        'subscription.plans.family.stripe_monthly_price' => 'price_family_monthly_test',
        'subscription.plans.family.stripe_yearly_price' => 'price_family_yearly_test',
    ]);
});

it('validates plan is required for checkout', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/subscription/checkout', []);

    $response->assertSessionHasErrors(['plan', 'billing_cycle']);
});

it('validates plan must be explorer or family', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/subscription/checkout', [
            'plan' => 'invalid',
            'billing_cycle' => 'monthly',
        ]);

    $response->assertSessionHasErrors(['plan']);
});

it('validates billing cycle must be monthly or yearly', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/subscription/checkout', [
            'plan' => 'explorer',
            'billing_cycle' => 'weekly',
        ]);

    $response->assertSessionHasErrors(['billing_cycle']);
});

it('prevents students from checking out', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($student)
        ->post('/parent/subscription/checkout', [
            'plan' => 'explorer',
            'billing_cycle' => 'monthly',
        ]);

    // Student middleware redirects students away from parent routes
    $response->assertRedirect();
});

it('prevents swap without active subscription', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/subscription/swap', [
            'plan' => 'family',
            'billing_cycle' => 'monthly',
        ]);

    $response->assertForbidden();
});

it('allows a parent to view the subscription management page', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/subscription');

    $response->assertOk();
});

it('allows a parent to view the subscription success page', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/subscription/success');

    $response->assertOk();
});
