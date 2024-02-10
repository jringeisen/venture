<?php

namespace App\Services\Charts\Interfaces;

use Illuminate\Support\Collection;

interface TimeframeStrategy
{
    public function fetchData(string $userId): Collection;

    public function getPeriod(): array;

    public function transformData(Collection $data): array;

    public function buildChartData(array $data): array;
}
