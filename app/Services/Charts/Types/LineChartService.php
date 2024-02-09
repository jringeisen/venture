<?php

namespace App\Services\Charts\Types;

class LineChartService
{
    public function __construct(
        private string $timeframe
    ) {
    }

    public function getDataForUser(mixed $userId): array
    {
        $strategyClass = 'App\Services\Charts\TimeframeStrategies\\'.ucfirst($this->timeframe).'Strategy';
        $strategy = new $strategyClass;

        $data = $strategy->fetchData($userId);
        $transformedData = $strategy->transformData($data);

        return $strategy->buildChartData($transformedData);
    }
}
