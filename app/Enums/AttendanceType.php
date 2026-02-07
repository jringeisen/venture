<?php

namespace App\Enums;

enum AttendanceType: string
{
    case Present = 'present';
    case FieldTrip = 'field_trip';
    case OfflineDay = 'offline_day';
    case ExcusedAbsence = 'excused_absence';

    public function label(): string
    {
        return match ($this) {
            self::Present => 'Present',
            self::FieldTrip => 'Field Trip',
            self::OfflineDay => 'Offline Day',
            self::ExcusedAbsence => 'Excused Absence',
        };
    }

    public static function toSelectArray(): array
    {
        return array_map(fn (self $case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
