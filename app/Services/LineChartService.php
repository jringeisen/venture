<?php

namespace App\Services;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LineChartService
{
    public array $data;

    public Collection $labels;

    public Collection $series;

    public function data(mixed $userId = null): self
    {
        $totalHoursPerMonth = DB::table('active_time')
            ->where('user_id', $userId)
            ->selectRaw('MONTH(created_at) as month, SUM(total_seconds) as total_seconds')
            ->groupBy('month')
            ->get()
            ->reduce(function ($carry, $item) {
                $monthName = Carbon::createFromFormat('!m', $item->month)->format('M');
                $carry[$monthName] = $item->total_seconds;

                return $carry;
            }, []);

        $monthsWithValues = array_merge($this->getBaseMonths(), $totalHoursPerMonth);

        $this->data = [
            'labels' => array_keys($monthsWithValues),
            'series' => array_values($monthsWithValues),
        ];

        return $this;
    }

    public function labels(string $label): self
    {
        $this->labels = collect($this->data[$label])->map(function (string $item) {
            return ucwords($item);
        })->values();

        return $this;
    }

    public function series(string $series): self
    {
        $this->series = collect($this->data[$series]);

        return $this;
    }

    public function get(): array
    {
        return [
            'labels' => $this->labels,
            'series' => $this->series,
        ];
    }

    protected function getBaseMonths(): array
    {
        $startOfYear = Carbon::parse(now()->startOfYear());
        $endOfYear = Carbon::parse(now()->endOfYear());

        $period = CarbonPeriod::create($startOfYear, '1 month', $endOfYear);
        $months = [];

        foreach ($period as $date) {
            $months[$date->format('M')] = 0;
        }

        return $months;
    }
}
