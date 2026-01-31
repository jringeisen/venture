<?php

use App\Enums\SubscriptionPlan;
use App\Models\User;
use App\Services\SubscriptionService;

beforeEach(function () {
    $this->service = app(SubscriptionService::class);

    config([
        'subscription.plans.explorer.stripe_monthly_price' => 'price_explorer_monthly_test',
        'subscription.plans.explorer.stripe_yearly_price' => 'price_explorer_yearly_test',
        'subscription.plans.family.stripe_monthly_price' => 'price_family_monthly_test',
        'subscription.plans.family.stripe_yearly_price' => 'price_family_yearly_test',
    ]);
});

it('gives a grandfathered parent the Family plan without a Stripe subscription', function () {
    $parent = User::factory()->parent()->grandfathered()->create();

    expect($this->service->getCurrentPlan($parent))->toBe(SubscriptionPlan::Family);
});

it('gives a grandfathered parent\'s student the Family plan', function () {
    $parent = User::factory()->parent()->grandfathered()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    expect($this->service->getCurrentPlan($student))->toBe(SubscriptionPlan::Family);
});

it('gives a non-grandfathered parent without subscription the Free plan', function () {
    $parent = User::factory()->parent()->create();

    expect($this->service->getCurrentPlan($parent))->toBe(SubscriptionPlan::Free);
});

it('allows a grandfathered parent unlimited questions', function () {
    $parent = User::factory()->parent()->grandfathered()->create();

    expect($this->service->canAskQuestion($parent))->toBeTrue();
});

it('allows a grandfathered parent unlimited course enrollments', function () {
    $parent = User::factory()->parent()->grandfathered()->create();
    $student = User::factory()->create([
        'parent_id' => $parent->id,
        'username' => fake()->userName(),
        'grade' => 5,
        'age' => 10,
    ]);

    $course = \App\Models\Course::factory()->create();
    $student->enrollInCourse($course);

    expect($this->service->canEnrollInCourse($student))->toBeTrue();
});

it('allows a grandfathered parent to generate compliance reports', function () {
    $parent = User::factory()->parent()->grandfathered()->create();

    expect($this->service->canGenerateReport($parent))->toBeTrue();
});

it('allows a grandfathered parent to download PDFs', function () {
    $parent = User::factory()->parent()->grandfathered()->create();

    expect($this->service->canDownloadCompliancePdf($parent))->toBeTrue();
});

it('allows a grandfathered parent to access certificates', function () {
    $parent = User::factory()->parent()->grandfathered()->create();

    expect($this->service->canAccessCertificates($parent))->toBeTrue();
});

it('does not grandfather new users by default', function () {
    $parent = User::factory()->parent()->create();

    expect($parent->grandfathered)->toBeFalse();
});

it('includes is_grandfathered in the subscription summary', function () {
    $parent = User::factory()->parent()->grandfathered()->create();

    $summary = $this->service->getSubscriptionSummary($parent);

    expect($summary['is_grandfathered'])->toBeTrue();
    expect($summary['plan'])->toBe('family');
});
