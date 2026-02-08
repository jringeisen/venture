<?php

use App\Models\User;
use Carbon\Carbon;

uses(\Tests\TestCase::class);

it('returns configured dates when set', function () {
    $user = User::factory()->make([
        'school_year_start' => '2025-09-01',
        'school_year_end' => '2026-06-15',
    ]);

    $range = $user->getSchoolYearRange();

    expect($range['start']->toDateString())->toBe('2025-09-01');
    expect($range['end']->toDateString())->toBe('2026-06-15');
});

it('returns default Aug-May range when dates not set', function () {
    $user = User::factory()->make([
        'school_year_start' => null,
        'school_year_end' => null,
    ]);

    Carbon::setTestNow(Carbon::create(2026, 2, 8));

    $range = $user->getSchoolYearRange();

    expect($range['start']->toDateString())->toBe('2025-08-01');
    expect($range['end']->toDateString())->toBe('2026-05-31');

    Carbon::setTestNow();
});

it('uses current year as start when month is August or later', function () {
    $user = User::factory()->make([
        'school_year_start' => null,
        'school_year_end' => null,
    ]);

    Carbon::setTestNow(Carbon::create(2025, 9, 15));

    $range = $user->getSchoolYearRange();

    expect($range['start']->toDateString())->toBe('2025-08-01');
    expect($range['end']->toDateString())->toBe('2026-05-31');

    Carbon::setTestNow();
});

it('uses previous year as start when month is before August', function () {
    $user = User::factory()->make([
        'school_year_start' => null,
        'school_year_end' => null,
    ]);

    Carbon::setTestNow(Carbon::create(2026, 4, 10));

    $range = $user->getSchoolYearRange();

    expect($range['start']->toDateString())->toBe('2025-08-01');
    expect($range['end']->toDateString())->toBe('2026-05-31');

    Carbon::setTestNow();
});
