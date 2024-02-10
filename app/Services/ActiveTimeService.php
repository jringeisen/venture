<?php

namespace App\Services;

use App\Models\ActiveTime;

class ActiveTimeService
{
    public function fetchTotalTimeForUser(int $userId, string $timeframe): string
    {
        $totalSecondsDuringTimeframe = ActiveTime::query()
            ->where('user_id', $userId)
            ->filterByTimeframe($timeframe)
            ->sum('total_seconds');

        return $this->formatActiveTime($totalSecondsDuringTimeframe);
    }

    protected function formatActiveTime(int $seconds): string
    {
        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        return $days.'d '.$hours.'h '.$minutes.'m';
    }
}
