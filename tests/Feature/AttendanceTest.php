<?php

use App\Enums\AttendanceType;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Events\Login;

beforeEach(function () {
    config([
        'subscription.plans.classroom.stripe_monthly_price' => 'price_classroom_monthly_test',
        'subscription.plans.classroom.stripe_yearly_price' => 'price_classroom_yearly_test',
        'subscription.plans.family.stripe_monthly_price' => 'price_family_monthly_test',
        'subscription.plans.family.stripe_yearly_price' => 'price_family_yearly_test',
    ]);
});

// --- Auto-track attendance on login ---

it('auto-creates a present attendance record when a student logs in', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    event(new Login('web', $student, false));

    $attendance = Attendance::where('user_id', $student->id)->first();

    expect($attendance)->not->toBeNull();
    expect($attendance->type)->toBe(AttendanceType::Present);
    expect($attendance->date->toDateString())->toBe(now()->timezone($student->timezone)->toDateString());
});

it('does not overwrite an existing attendance record on login', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    Attendance::factory()->fieldTrip()->forStudent($student)->create([
        'date' => now()->timezone($student->timezone)->toDateString(),
    ]);

    event(new Login('web', $student, false));

    $attendance = Attendance::where('user_id', $student->id)->first();

    expect($attendance->type)->toBe(AttendanceType::FieldTrip);
});

it('does not create attendance for a parent login', function () {
    $parent = User::factory()->parent()->create();

    event(new Login('web', $parent, false));

    expect(Attendance::where('user_id', $parent->id)->count())->toBe(0);
});

// --- Parent attendance index ---

it('allows a parent to view the attendance calendar page', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/attendance');

    $response->assertOk();
});

it('shows attendance data for a specific student', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    Attendance::factory()->forStudent($student)->create([
        'date' => now()->startOfMonth()->addDays(5)->toDateString(),
    ]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/attendance?student_id='.$student->id.'&year='.now()->year.'&month='.now()->month);

    $response->assertOk();
});

// --- Parent CRUD ---

it('allows a parent to add an attendance record for their student', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/attendance', [
            'student_id' => $student->id,
            'date' => '2026-01-15',
            'type' => 'field_trip',
            'notes' => 'Science museum visit',
        ]);

    $response->assertRedirect();

    $attendance = Attendance::where('user_id', $student->id)->where('date', '2026-01-15')->first();

    expect($attendance)->not->toBeNull();
    expect($attendance->type)->toBe(AttendanceType::FieldTrip);
    expect($attendance->notes)->toBe('Science museum visit');
    expect($attendance->created_by)->toBe($parent->id);
});

it('prevents a parent from adding attendance for another parents student', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);
    $otherParent = User::factory()->parent()->create();
    $otherStudent = User::factory()->create(['parent_id' => $otherParent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/attendance', [
            'student_id' => $otherStudent->id,
            'date' => '2026-01-15',
            'type' => 'present',
        ]);

    $response->assertForbidden();
});

it('allows a parent to update an attendance record', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $attendance = Attendance::factory()->forStudent($student)->create([
        'date' => '2026-01-15',
        'type' => AttendanceType::Present,
    ]);

    $response = $this
        ->actingAs($parent)
        ->put("/parent/attendance/{$attendance->id}", [
            'type' => 'field_trip',
            'notes' => 'Changed to field trip',
        ]);

    $response->assertRedirect();

    $attendance->refresh();
    expect($attendance->type)->toBe(AttendanceType::FieldTrip);
    expect($attendance->notes)->toBe('Changed to field trip');
});

it('prevents a parent from updating another parents attendance record', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);
    $otherParent = User::factory()->parent()->create();
    $otherStudent = User::factory()->create(['parent_id' => $otherParent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $attendance = Attendance::factory()->forStudent($otherStudent)->create();

    $response = $this
        ->actingAs($parent)
        ->put("/parent/attendance/{$attendance->id}", [
            'type' => 'field_trip',
        ]);

    $response->assertForbidden();
});

it('allows a parent to delete an attendance record', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $attendance = Attendance::factory()->forStudent($student)->create();

    $response = $this
        ->actingAs($parent)
        ->delete("/parent/attendance/{$attendance->id}");

    $response->assertRedirect();
    expect(Attendance::find($attendance->id))->toBeNull();
});

it('prevents a parent from deleting another parents attendance record', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);
    $otherParent = User::factory()->parent()->create();
    $otherStudent = User::factory()->create(['parent_id' => $otherParent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $attendance = Attendance::factory()->forStudent($otherStudent)->create();

    $response = $this
        ->actingAs($parent)
        ->delete("/parent/attendance/{$attendance->id}");

    $response->assertForbidden();
});

// --- Validation ---

it('validates required fields when adding attendance', function () {
    $parent = User::factory()->parent()->create();
    User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/attendance', []);

    $response->assertSessionHasErrors(['student_id', 'date', 'type']);
});

it('validates type must be a valid attendance type', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->post('/parent/attendance', [
            'student_id' => $student->id,
            'date' => '2026-01-15',
            'type' => 'invalid_type',
        ]);

    $response->assertSessionHasErrors(['type']);
});

// --- Daily summary ---

it('returns daily work overview as json', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/attendance/daily-summary?student_id='.$student->id.'&date=2026-01-15');

    $response->assertOk();
    $response->assertJsonStructure(['active_time_seconds', 'courses', 'questions', 'question_count']);
});

// --- Student field trip marking ---

it('allows a student to mark today as a field trip', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $today = now()->timezone($student->timezone)->toDateString();

    Attendance::factory()->forStudent($student)->create([
        'date' => $today,
        'type' => AttendanceType::Present,
    ]);

    $response = $this
        ->actingAs($student)
        ->patch('/student/attendance/today');

    $response->assertRedirect();

    $attendance = Attendance::where('user_id', $student->id)->where('date', $today)->first();
    expect($attendance->type)->toBe(AttendanceType::FieldTrip);
});

it('creates a field trip record if none exists for today', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($student)
        ->patch('/student/attendance/today');

    $response->assertRedirect();

    $today = now()->timezone($student->timezone)->toDateString();
    $attendance = Attendance::where('user_id', $student->id)->where('date', $today)->first();

    expect($attendance)->not->toBeNull();
    expect($attendance->type)->toBe(AttendanceType::FieldTrip);
});

// --- Student dashboard shows attendance ---

it('shows attendance data on student dashboard', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    Attendance::factory()->forStudent($student)->create([
        'date' => now()->timezone($student->timezone)->toDateString(),
        'type' => AttendanceType::Present,
    ]);

    $response = $this
        ->actingAs($student)
        ->get('/student/dashboard');

    $response->assertOk();
});

// --- Unique constraint ---

it('enforces one attendance per student per day', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    Attendance::factory()->forStudent($student)->create(['date' => '2026-01-15']);

    // Adding attendance for the same date via store should update, not duplicate
    $response = $this
        ->actingAs($parent)
        ->post('/parent/attendance', [
            'student_id' => $student->id,
            'date' => '2026-01-15',
            'type' => 'offline_day',
            'notes' => 'Library day',
        ]);

    $response->assertRedirect();
    expect(Attendance::where('user_id', $student->id)->where('date', '2026-01-15')->count())->toBe(1);
    expect(Attendance::where('user_id', $student->id)->where('date', '2026-01-15')->first()->type)->toBe(AttendanceType::OfflineDay);
});

// --- AttendanceService ---

it('returns correct attendance summary counts', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    Attendance::factory()->forStudent($student)->create(['date' => '2026-01-01', 'type' => AttendanceType::Present]);
    Attendance::factory()->forStudent($student)->create(['date' => '2026-01-02', 'type' => AttendanceType::Present]);
    Attendance::factory()->forStudent($student)->create(['date' => '2026-01-03', 'type' => AttendanceType::FieldTrip]);
    Attendance::factory()->forStudent($student)->create(['date' => '2026-01-04', 'type' => AttendanceType::OfflineDay]);
    Attendance::factory()->forStudent($student)->create(['date' => '2026-01-05', 'type' => AttendanceType::ExcusedAbsence]);

    $service = app(\App\Services\AttendanceService::class);
    $summary = $service->getAttendanceSummary(
        $student,
        \Carbon\Carbon::parse('2026-01-01'),
        \Carbon\Carbon::parse('2026-01-31')
    );

    expect($summary['present'])->toBe(2);
    expect($summary['field_trip'])->toBe(1);
    expect($summary['offline_day'])->toBe(1);
    expect($summary['excused_absence'])->toBe(1);
    expect($summary['total_attendance_days'])->toBe(4);
});

// --- Year-to-date summary ---

it('includes year summary in attendance index', function () {
    $parent = User::factory()->parent()->create([
        'school_year_start' => '2025-08-01',
        'school_year_end' => '2026-05-31',
    ]);
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    Attendance::factory()->forStudent($student)->create(['date' => '2025-09-15', 'type' => AttendanceType::Present]);
    Attendance::factory()->forStudent($student)->create(['date' => '2025-10-20', 'type' => AttendanceType::FieldTrip]);
    Attendance::factory()->forStudent($student)->create(['date' => '2026-01-10', 'type' => AttendanceType::Present]);
    Attendance::factory()->forStudent($student)->create(['date' => '2026-02-05', 'type' => AttendanceType::OfflineDay]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/attendance?student_id='.$student->id.'&year=2026&month=2');

    $response->assertOk();

    $yearSummary = $response->original->getData()['page']['props']['yearSummary'];

    expect($yearSummary['present'])->toBe(2);
    expect($yearSummary['field_trip'])->toBe(1);
    expect($yearSummary['offline_day'])->toBe(1);
    expect($yearSummary['total_attendance_days'])->toBe(4);
});

it('uses default school year range when dates not configured', function () {
    $parent = User::factory()->parent()->create();
    $student = User::factory()->create(['parent_id' => $parent->id, 'username' => fake()->userName(), 'grade' => 5, 'age' => 10]);

    $response = $this
        ->actingAs($parent)
        ->get('/parent/attendance?student_id='.$student->id);

    $response->assertOk();

    $yearSummary = $response->original->getData()['page']['props']['yearSummary'];

    expect($yearSummary)->not->toBeNull();
    expect($yearSummary)->toHaveKeys(['present', 'field_trip', 'offline_day', 'excused_absence', 'total_attendance_days']);
});
