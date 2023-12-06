<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PieChartService
{
    public Collection $data;

    public Collection $labels;

    public Collection $series;

    public function data(string $model, string $column, mixed $userId = null): self
    {
        $this->data = $model::select($column, DB::raw('count(*) as total'))
            ->when($userId, function ($query, $userId) {
                $query->whereHas('promptQuestion', function ($query) use ($userId) {
                    $query->where('student_id', $userId);
                });
            })
            ->whereNotNull($column)
            ->groupBy($column)
            ->get()
            ->map(function ($item) use ($column) {
                $item->$column = ucfirst($item->$column);

                return $item;
            })
            ->values();

        return $this;
    }

    public function labels(string $label): self
    {
        $this->labels = $this->data->pluck($label)->map(function ($item) {
            return ucwords($item);
        })->values();

        return $this;
    }

    public function series(string $series): self
    {
        $this->series = $this->data->pluck($series);

        return $this;
    }

    public function get(): array
    {
        return [
            'labels' => $this->labels,
            'series' => $this->series,
        ];
    }
}
