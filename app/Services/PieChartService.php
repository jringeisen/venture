<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PieChartService
{
    public Collection $data;

    public Collection $labels;

    public Collection $series;

    public function data(string $model, string $column, mixed $userId = null): self
    {
        $query = $model::when($userId, function (Builder $query, int $userId) {
            $query->whereHas('promptQuestion', function (Builder $query) use ($userId) {
                $query->where('student_id', $userId);
            });
        });

        $this->data = $query->select($column, DB::raw('count(*) as total'))
            ->whereNotNull($column)
            ->groupBy($column)
            ->get()
            ->map(function (Model $item) use ($column) {
                $item->$column = ucfirst($item->$column);

                return $item;
            })
            ->values();

        return $this;
    }

    public function labels(string $label): self
    {
        $this->labels = $this->data->pluck($label)->map(function (string $item) {
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
