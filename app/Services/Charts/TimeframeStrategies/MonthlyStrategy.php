<?php

namespace App\Services\Charts\TimeframeStrategies;

use App\Models\ActiveTime;
use App\Services\Charts\Interfaces\TimeframeStrategy;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MonthlyStrategy implements TimeframeStrategy
{
    public function fetchData(mixed $userId = null): Collection
    {
        return ActiveTime::query()
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->when(! $userId, fn ($query) => $query->whereHas('user', fn ($query) => $query->where('parent_id', request()->user()->id)))
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
