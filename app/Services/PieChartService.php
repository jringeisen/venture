<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PieChartService
{
    public Collection $data;

    public Collection $labels;

    public Collection $series;

    public function data(Collection $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function labels(string $label): self
    {
        $this->labels = $this->data->pluck($label)->filter()->map(function ($item) {
            return ucwords($item);
        })->values();

        return $this;
    }

    public function series(string $series): self
    {
        $this->series = $this->data->pluck($series)->filter()->values();

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
