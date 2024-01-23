<?php

namespace App\Enums;

enum FeedbackStatuses: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case ON_HOLD = 'on_hold';
    case RESOLVED = 'resolved';
    case REOPENED = 'reopened';
    case CANCELLED = 'cancelled';

    public static function toSelectArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function ($case) {
                return [$case->value => ucwords(str_replace('_', ' ', $case->name))];
            })
            ->toArray();
    }
}
