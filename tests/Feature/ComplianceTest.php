<?php

use App\Models\ComplianceReport;
use App\Models\User;

it('allows a parent to view the compliance index page', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/compliance');

    $response->assertOk();
});

it('allows a parent to view the compliance report create page', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/compliance/reports/create');

    $response->assertOk();
});

it('allows a parent to generate a compliance report', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $student->id,
            'state' => 'FL',
            'period_start' => now()->subMonths(3)->toDateString(),
            'period_end' => now()->toDateString(),
            'title' => 'Test Compliance Report',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $report = ComplianceReport::where('parent_id', $parent->id)->where('student_id', $student->id)->first();

    expect($report)->not->toBeNull();
    expect($report->title)->toBe('Test Compliance Report');
    expect($report->state->value)->toBe('FL');
    expect($report->status->value)->toBe('generated');
    expect($report->summary_statistics)->toBeArray();
});

it('auto-generates a title when none is provided', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $student->id,
            'state' => 'FL',
            'period_start' => now()->subMonths(3)->toDateString(),
            'period_end' => now()->toDateString(),
        ]);

    $response->assertSessionHasNoErrors();

    $report = ComplianceReport::where('parent_id', $parent->id)->first();

    expect($report)->not->toBeNull();
    expect($report->title)->toContain('Florida Compliance Report');
});

it('validates required fields when generating a report', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', []);

    $response->assertSessionHasErrors(['student_id', 'state', 'period_start', 'period_end']);
});

it('prevents a parent from generating a report for another parents student', function () {
    $parent = User::factory()->parent()->create();
    $otherParent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);
    $otherStudent = User::factory()->create(['parent_id' => $otherParent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $otherStudent->id,
            'state' => 'FL',
            'period_start' => now()->subMonths(3)->toDateString(),
            'period_end' => now()->toDateString(),
        ]);

    $response->assertForbidden();
});

it('allows a parent to view their own compliance report', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $report = ComplianceReport::factory()->forParentAndStudent($parent, $student)->create();

    $response = $this
        ->actingAs($parent)
        ->get("/parent/compliance/reports/{$report->id}");

    $response->assertOk();
});

it('prevents a parent from viewing another parents report', function () {
    $parent = User::factory()->parent()->create();
    $otherParent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);
    $otherStudent = User::factory()->create(['parent_id' => $otherParent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $report = ComplianceReport::factory()->forParentAndStudent($otherParent, $otherStudent)->create();

    $response = $this
        ->actingAs($parent)
        ->get("/parent/compliance/reports/{$report->id}");

    $response->assertForbidden();
});

it('allows a parent to download a compliance report as pdf', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $report = ComplianceReport::factory()->forParentAndStudent($parent, $student)->create();

    $response = $this
        ->actingAs($parent)
        ->get("/parent/compliance/reports/{$report->id}/pdf");

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
});

it('allows a parent to delete their own compliance report', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $report = ComplianceReport::factory()->forParentAndStudent($parent, $student)->create();

    expect(ComplianceReport::where('id', $report->id)->count())->toBe(1);

    $response = $this
        ->actingAs($parent)
        ->delete("/parent/compliance/reports/{$report->id}");

    $response->assertRedirect('/parent/compliance');

    expect(ComplianceReport::where('id', $report->id)->count())->toBe(0);
});

it('prevents a parent from deleting another parents report', function () {
    $parent = User::factory()->parent()->create();
    $otherParent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);
    $otherStudent = User::factory()->create(['parent_id' => $otherParent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $report = ComplianceReport::factory()->forParentAndStudent($otherParent, $otherStudent)->create();

    $response = $this
        ->actingAs($parent)
        ->delete("/parent/compliance/reports/{$report->id}");

    $response->assertForbidden();
});

it('validates period_end must be after period_start', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $student->id,
            'state' => 'FL',
            'period_start' => now()->toDateString(),
            'period_end' => now()->subMonth()->toDateString(),
        ]);

    $response->assertSessionHasErrors(['period_end']);
});

it('validates state must be a valid compliance state', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $student->id,
            'state' => 'XX',
            'period_start' => now()->subMonths(3)->toDateString(),
            'period_end' => now()->toDateString(),
        ]);

    $response->assertSessionHasErrors(['state']);
});
