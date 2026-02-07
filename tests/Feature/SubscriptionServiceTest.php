<?php

use App\Enums\SubscriptionPlan;
use App\Models\ComplianceReport;
use App\Models\DailyQuestionCount;
use App\Models\User;
use App\Services\SubscriptionService;

beforeEach(function () {
    $this->service = app(SubscriptionService::class);

    // Set test price IDs in config so plan resolution works
    config([
        'subscription.plans.family.stripe_monthly_price' => 'price_family_monthly_test',
        'subscription.plans.family.stripe_yearly_price' => 'price_family_yearly_test',
        'subscription.plans.classroom.stripe_monthly_price' => 'price_classroom_monthly_test',
        'subscription.plans.classroom.stripe_yearly_price' => 'price_classroom_yearly_test',
    ]);
});

it('resolves free plan for a user with no subscription', function () {
    $parent = User::factory()->parent()->create();

    expect($this->service->getCurrentPlan($parent))->toBe(SubscriptionPlan::Free);
});

it('resolves family plan from stripe price', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();

    expect($this->service->getCurrentPlan($parent))->toBe(SubscriptionPlan::Family);
});

it('resolves classroom plan from stripe price', function () {
    $parent = User::factory()->parent()->subscribed('classroom')->create();

    expect($this->service->getCurrentPlan($parent))->toBe(SubscriptionPlan::Classroom);
});

it('resolves billing user from parent', function () {
    $parent = User::factory()->parent()->create();

    expect($this->service->getBillingUser($parent)->id)->toBe($parent->id);
});

it('resolves billing user from student', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    expect($this->service->getBillingUser($student)->id)->toBe($parent->id);
});

it('allows questions when under hourly limit', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    expect($this->service->canAskQuestion($student))->toBeTrue();
});

it('blocks questions when at hourly limit', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    DailyQuestionCount::create([
        'user_id' => $student->id,
        'parent_id' => $parent->id,
        'date' => now($parent->timezone)->toDateString(),
        'hour' => now($parent->timezone)->hour,
        'count' => 10,
    ]);

    expect($this->service->canAskQuestion($student))->toBeFalse();
});

it('allows questions in a new hour even if previous hour was at limit', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $previousHour = now($parent->timezone)->subHour()->hour;

    DailyQuestionCount::create([
        'user_id' => $student->id,
        'parent_id' => $parent->id,
        'date' => now($parent->timezone)->toDateString(),
        'hour' => $previousHour,
        'count' => 10,
    ]);

    expect($this->service->canAskQuestion($student))->toBeTrue();
});

it('tracks questions per student independently', function () {
    $parent = User::factory()->parent()->create();
    $student1 = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);
    $student2 = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 7, 'age' => 12]);

    DailyQuestionCount::create([
        'user_id' => $student1->id,
        'parent_id' => $parent->id,
        'date' => now($parent->timezone)->toDateString(),
        'hour' => now($parent->timezone)->hour,
        'count' => 10,
    ]);

    expect($this->service->canAskQuestion($student1))->toBeFalse();
    expect($this->service->canAskQuestion($student2))->toBeTrue();
});

it('allows unlimited questions on family plan', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    DailyQuestionCount::create([
        'user_id' => $student->id,
        'parent_id' => $parent->id,
        'date' => now($parent->timezone)->toDateString(),
        'hour' => now($parent->timezone)->hour,
        'count' => 100,
    ]);

    expect($this->service->canAskQuestion($student))->toBeTrue();
});

it('allows unlimited questions on classroom plan', function () {
    $parent = User::factory()->parent()->subscribed('classroom')->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    DailyQuestionCount::create([
        'user_id' => $student->id,
        'parent_id' => $parent->id,
        'date' => now($parent->timezone)->toDateString(),
        'hour' => now($parent->timezone)->hour,
        'count' => 100,
    ]);

    expect($this->service->canAskQuestion($student))->toBeTrue();
});

it('records a question per student per hour', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $this->service->recordQuestion($student);

    $record = DailyQuestionCount::where('user_id', $student->id)
        ->where('date', now($parent->timezone)->toDateString())
        ->where('hour', now($parent->timezone)->hour)
        ->first();

    expect($record)->not->toBeNull();
    expect($record->count)->toBe(1);
});

it('increments existing hourly count on subsequent questions', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $this->service->recordQuestion($student);
    $this->service->recordQuestion($student);

    $count = DailyQuestionCount::where('user_id', $student->id)
        ->where('date', now($parent->timezone)->toDateString())
        ->where('hour', now($parent->timezone)->hour)
        ->value('count');

    expect($count)->toBe(2);
});

it('blocks course enrollment when at free tier limit', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    // Create an active enrollment
    $course = \App\Models\Course::factory()->create();
    $student->enrollInCourse($course);

    // Free tier allows 1 active course
    expect($this->service->canEnrollInCourse($student))->toBeFalse();
});

it('allows course enrollment on family plan', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $course = \App\Models\Course::factory()->create();
    $student->enrollInCourse($course);

    expect($this->service->canEnrollInCourse($student))->toBeTrue();
});

it('blocks compliance reports on free tier', function () {
    $parent = User::factory()->parent()->create();

    expect($this->service->canGenerateReport($parent))->toBeFalse();
});

it('allows unlimited compliance reports on family tier', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();

    ComplianceReport::factory()->forParentAndStudent(
        $parent,
        User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10])
    )->count(10)->create();

    expect($this->service->canGenerateReport($parent))->toBeTrue();
});

it('allows unlimited compliance reports on classroom tier', function () {
    $parent = User::factory()->parent()->subscribed('classroom')->create();

    ComplianceReport::factory()->forParentAndStudent(
        $parent,
        User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10])
    )->count(10)->create();

    expect($this->service->canGenerateReport($parent))->toBeTrue();
});

it('blocks adding students when at free tier limit', function () {
    $parent = User::factory()->parent()->create();

    // Free tier allows 1 student
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    expect($this->service->canAddStudent($parent))->toBeFalse();
});

it('allows adding students on family plan up to 5', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();

    for ($i = 0; $i < 4; $i++) {
        User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->unique()->userName(), 'grade' => 5, 'age' => 10]);
    }

    expect($this->service->canAddStudent($parent))->toBeTrue();
});

it('blocks adding students on family plan at limit', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();

    for ($i = 0; $i < 5; $i++) {
        User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->unique()->userName(), 'grade' => 5, 'age' => 10]);
    }

    expect($this->service->canAddStudent($parent))->toBeFalse();
});

it('allows adding students on classroom plan up to 25', function () {
    $parent = User::factory()->parent()->subscribed('classroom')->create();

    for ($i = 0; $i < 24; $i++) {
        User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->unique()->userName(), 'grade' => 5, 'age' => 10]);
    }

    expect($this->service->canAddStudent($parent))->toBeTrue();
});

it('blocks PDF download on free tier', function () {
    $parent = User::factory()->parent()->create();

    expect($this->service->canDownloadCompliancePdf($parent))->toBeFalse();
});

it('allows PDF download on family tier', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();

    expect($this->service->canDownloadCompliancePdf($parent))->toBeTrue();
});

it('allows PDF download on classroom tier', function () {
    $parent = User::factory()->parent()->subscribed('classroom')->create();

    expect($this->service->canDownloadCompliancePdf($parent))->toBeTrue();
});

it('blocks certificates on free tier', function () {
    $parent = User::factory()->parent()->create();

    expect($this->service->canAccessCertificates($parent))->toBeFalse();
});

it('allows certificates on family tier', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();

    expect($this->service->canAccessCertificates($parent))->toBeTrue();
});

it('allows certificates on classroom tier', function () {
    $parent = User::factory()->parent()->subscribed('classroom')->create();

    expect($this->service->canAccessCertificates($parent))->toBeTrue();
});

it('returns a complete subscription summary', function () {
    $parent = User::factory()->parent()->subscribed('family')->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $summary = $this->service->getSubscriptionSummary($parent);

    expect($summary)->toHaveKeys(['plan', 'plan_label', 'is_active', 'on_grace_period', 'limits', 'usage', 'can']);
    expect($summary['plan'])->toBe('family');
    expect($summary['plan_label'])->toBe('Family');
    expect($summary['is_active'])->toBeTrue();
    expect($summary['usage'])->toHaveKeys(['ai_questions_this_hour', 'students', 'compliance_reports_this_month']);
    expect($summary['can'])->toHaveKeys(['ask_question', 'enroll_in_course', 'generate_report', 'add_student', 'download_pdf', 'access_certificates']);
});

it('resolves family plan for grandfathered users', function () {
    $parent = User::factory()->parent()->grandfathered()->create();

    expect($this->service->getCurrentPlan($parent))->toBe(SubscriptionPlan::Family);
});
