<?php

namespace App\Enums;

enum CourseSubjects: string
{
    case NATURAL_SCIENCES = 'natural_sciences';
    case SOCIAL_SCIENCES = 'social_sciences';
    case FORMAL_SCIENCES = 'formal_sciences';
    case HUMANITIES = 'humanities';
    case APPLIED_SCIENCES = 'applied_sciences';
    case BUSINESS = 'business';
    case LIFE_SKILLS = 'life_skills';
    case FINANCIAL = 'financial';
    case MATHEMATCIS = 'mathematics';
    case HISTORY = 'history';
    case ENGLISH = 'english';

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
