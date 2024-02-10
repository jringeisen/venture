<?php

namespace App\Services\Stats\ActiveTime;

abstract class TimeStrategy
{
    protected function formatActiveTime(int $seconds): string
    {
        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        return $days.'d '.$hours.'h '.$minutes.'m';
    }
}
