<?php

namespace App\Services\Charts\TimeframeStrategies;

use App\Services\Charts\Interfaces\TimeframeStrategy;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WeeklyStrategy implements TimeframeStrategy
{
    public function fetchData(string $userId): Collection
    {
        return DB::table('active_time')
            ->where('user_id', $userId)
            ->whereBetween('date', [
                now(request()->user()->timezone)->startOfWeek(),
                now(request()->user()->timezone)->endOfWeek(),
            ])
            ->selectRaw('DAY(date) as day, SUM(total_seconds) as total_seconds')
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    public function getPeriod(): array
    {
        $startOfWeek = now(request()->user()->timezone)->startOfWeek();
        $endOfWeek = now(request()->user()->timezone)->endOfWeek();

        $period = CarbonPeriod::create($startOfWeek, '1 day', $endOfWeek);
        $days = [];

        foreach ($period as $date) {
            $days[$date->format('l')] = 0;
        }

        return $days;
    }

    public function transformData($data): array
    {
        $totalHoursPerDay = $data->reduce(function ($carry, $item) {
            $day = Carbon::createFromFormat('!j', $item->day)->format('l');
            $carry[$day] = (int) $item->total_seconds;

            return $carry;
        }, []);

        return array_replace($this->getPeriod(), $totalHoursPerDay);
    }

    public function buildChartData(array $data): array
    {
        return [
            'labels' => array_keys($data),
            'series' => array_values($data),
        ];
    }
}
