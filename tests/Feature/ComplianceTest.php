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
    expect($report->period_start->toDateString())->toBe(now()->subYears(2)->toDateString());
    expect($report->period_end->toDateString())->toBe(now()->toDateString());
});

it('auto-generates a title when none is provided', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $student->id,
            'state' => 'FL',
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

    $response->assertSessionHasErrors(['student_id', 'state']);
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

it('validates state must be a valid compliance state', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/compliance/reports', [
            'student_id' => $student->id,
            'state' => 'XX',
        ]);

    $response->assertSessionHasErrors(['state']);
});
