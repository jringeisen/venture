<?php

namespace App\Services\Charts\Types;

class LineChartService
{
    public function getDataForUser(string $timeframe, mixed $userId = null): array
    {
        $strategyClass = 'App\Services\Charts\TimeframeStrategies\\'.ucfirst($timeframe).'Strategy';
        $strategy = new $strategyClass;

        $data = $strategy->fetchData($userId);
        $transformedData = $strategy->transformData($data);

        return $strategy->buildChartData($transformedData);
    }
}
