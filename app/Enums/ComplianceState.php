<?php

namespace App\Enums;

enum ComplianceState: string
{
    case FL = 'FL';

    public function label(): string
    {
        return match ($this) {
            self::FL => 'Florida',
        };
    }

    public static function toSelectArray(): array
    {
        return collect(self::cases())
            ->mapWithKeys(function ($case) {
                return [$case->value => $case->label()];
            })
            ->toArray();
    }
}
