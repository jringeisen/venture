<?php

namespace App\Services\Charts\TimeframeStrategies;

use App\Services\Charts\Interfaces\TimeframeStrategy;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class YearlyStrategy implements TimeframeStrategy
{
    public function fetchData(string $userId): Collection
    {
        return DB::table('active_time')
            ->where('user_id', $userId)
            ->selectRaw('MONTH(date) as month, SUM(total_seconds) as total_seconds')
            ->groupBy('month')
            ->get();
    }

    public function getPeriod(): array
    {
        $startOfYear = now(request()->user()->timezone)->startOfYear();
        $endOfYear = now(request()->user()->timezone)->endOfYear();

        $period = CarbonPeriod::create($startOfYear, '1 month', $endOfYear);
        $months = [];

        foreach ($period as $date) {
            $months[$date->format('M')] = 0;
        }

        return $months;
    }

    public function transformData($data): array
    {
        $totalHoursPerMonth = $data->reduce(function ($carry, $item) {
            $monthName = Carbon::createFromFormat('!m', $item->month)->format('M');
            $carry[$monthName] = (int) $item->total_seconds;

            return $carry;
        }, []);

        return array_replace($this->getPeriod(), $totalHoursPerMonth);
    }

    public function buildChartData(array $data): array
    {
        return [
            'labels' => array_keys($data),
            'series' => array_values($data),
        ];
    }
}
