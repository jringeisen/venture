<?php

namespace App\Enums;

enum CourseLengths: string
{
    case THIRTY_SIX_WEEKS = '36_weeks';
    case EIGHTEEN_WEEKS = '18_weeks';
    case NINE_WEEKS = '9_weeks';
    case ONE_WEEK = '1_week';

    public static function toSelectArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function ($case) {
                return [$case->value => ucwords(str_replace('_', ' ', $case->value))];
            })
            ->toArray();
    }
}
