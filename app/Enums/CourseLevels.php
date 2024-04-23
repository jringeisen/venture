<?php

namespace App\Enums;

enum CourseLevels: string
{
    case ELEMENTARY = 'elementary';
    case MIDDLE = 'middle';
    case HIGH = 'high';

    public static function toSelectArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function ($case) {
                return [$case->value => ucwords(str_replace('_', ' ', $case->value))];
            })
            ->toArray();
    }

    public static function getRandomValue(): string
    {
        return collect(self::cases())->random()->value;
    }
}
