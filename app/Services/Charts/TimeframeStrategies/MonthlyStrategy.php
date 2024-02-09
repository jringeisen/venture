<?php

namespace App\Services\Charts\TimeframeStrategies;

use App\Services\Charts\Interfaces\TimeframeStrategy;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MonthlyStrategy implements TimeframeStrategy
{
    public function fetchData(string $userId): Collection
    {
        return DB::table('active_time')
            ->where('user_id', $userId)
            ->whereBetween('created_at', [
                now(request()->user()->timezone)->startOfMonth(),
                now(request()->user()->timezone)->endOfMonth(),
            ])
            ->selectRaw('DAY(created_at) as day, SUM(total_seconds) as total_seconds')
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    public function getPeriod(): array
    {
        $startOfMonth = now(request()->user()->timezone)->startOfMonth();
        $endOfMonth = now(request()->user()->timezone)->endOfMonth();

        $period = CarbonPeriod::create($startOfMonth, '1 day', $endOfMonth);
        $days = [];

        foreach ($period as $date) {
            $days[$date->format('j')] = 0;
        }

        return $days;
    }

    public function transformData($data): array
    {
        $totalHoursPerDay = $data->reduce(function ($carry, $item) {
            $day = Carbon::createFromFormat('j', $item->day)->format('j');
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
