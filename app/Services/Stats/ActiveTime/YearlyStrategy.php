<?php

namespace App\Services\Stats\ActiveTime;

use App\Models\ActiveTime;
use App\Services\Stats\ActiveTime\Interfaces\TimeframeStrategyInterface;

class YearlyStrategy extends TimeStrategy implements TimeframeStrategyInterface
{
    public function fetchFormattedDataForUser(mixed $userId): string
    {
        return $this->formatActiveTime(
            $this->fetchSumOfTotalSecondsForUser($userId)
        );
    }

    public function fetchSumOfTotalSecondsForUser(mixed $userId): int
    {
        return ActiveTime::query()
            ->where('user_id', $userId)
            ->whereYear('created_at', now(request()->user()->timezone)->year)
            ->sum('total_seconds');
    }
}
