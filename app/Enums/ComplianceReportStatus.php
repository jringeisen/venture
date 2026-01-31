<?php

namespace App\Enums;

enum ComplianceReportStatus: string
{
    case Generated = 'generated';
    case Reviewed = 'reviewed';
    case Submitted = 'submitted';

    public static function toSelectArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function ($case) {
                return [$case->value => ucwords($case->name)];
            })
            ->toArray();
    }
}
