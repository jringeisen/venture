<?php

namespace App\Services\Stats\ActiveTime\Interfaces;

interface TimeframeStrategyInterface
{
    public function fetchFormattedDataForUser(mixed $userId): string;

    public function fetchSumOfTotalSecondsForUser(mixed $userId): int;
}
