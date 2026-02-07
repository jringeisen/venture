<?php

use App\Enums\AgeGroup;
use App\Models\Course;

it('has three school level cases', function () {
    $cases = AgeGroup::cases();

    expect($cases)->toHaveCount(3);
    expect(array_map(fn ($c) => $c->value, $cases))->toBe(['elementary', 'middle', 'high']);
});

it('returns correct age ranges for elementary', function () {
    $group = AgeGroup::Elementary;

    expect($group->minAge())->toBe(5);
    expect($group->maxAge())->toBe(10);
});

it('returns correct age ranges for middle', function () {
    $group = AgeGroup::Middle;

    expect($group->minAge())->toBe(11);
    expect($group->maxAge())->toBe(13);
});

it('returns correct age ranges for high', function () {
    $group = AgeGroup::High;

    expect($group->minAge())->toBe(14);
    expect($group->maxAge())->toBe(18);
});

it('returns labels for all groups', function () {
    expect(AgeGroup::Elementary->label())->toContain('Elementary');
    expect(AgeGroup::Middle->label())->toContain('Middle');
    expect(AgeGroup::High->label())->toContain('High');
});

it('returns content guidelines for all groups', function () {
    foreach (AgeGroup::cases() as $case) {
        expect($case->contentGuidelines())->toBeString()->not->toBeEmpty();
    }
});

it('resolves from value', function () {
    expect(AgeGroup::fromValue('elementary'))->toBe(AgeGroup::Elementary);
    expect(AgeGroup::fromValue('middle'))->toBe(AgeGroup::Middle);
    expect(AgeGroup::fromValue('high'))->toBe(AgeGroup::High);
    expect(AgeGroup::fromValue('invalid'))->toBeNull();
});

it('resolves from min max age', function () {
    expect(AgeGroup::fromMinMaxAge(5, 10))->toBe(AgeGroup::Elementary);
    expect(AgeGroup::fromMinMaxAge(11, 13))->toBe(AgeGroup::Middle);
    expect(AgeGroup::fromMinMaxAge(14, 18))->toBe(AgeGroup::High);
    expect(AgeGroup::fromMinMaxAge(5, 6))->toBeNull();
});

it('generates select array with correct structure', function () {
    $array = AgeGroup::toSelectArray();

    expect($array)->toHaveCount(3);
    expect($array[0])->toHaveKeys(['value', 'label', 'min_age', 'max_age']);
    expect($array[0]['value'])->toBe('elementary');
});

it('course resolves age group from min max age', function () {
    $course = Course::factory()->elementary()->create();

    expect($course->age_group)->toBe(AgeGroup::Elementary);
    expect($course->age_group_label)->toContain('Elementary');
});

it('course scope filters by age correctly', function () {
    Course::factory()->elementary()->create();
    Course::factory()->middle()->create();
    Course::factory()->high()->create();
    Course::factory()->create(); // all ages

    expect(Course::forAge(8)->count())->toBe(2); // elementary + all ages
    expect(Course::forAge(12)->count())->toBe(2); // middle + all ages
    expect(Course::forAge(16)->count())->toBe(2); // high + all ages
    expect(Course::forAge(null)->count())->toBe(4); // all courses
});
