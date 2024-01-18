<?php

namespace App\Nova\Metrics\Dashboards\Trends;

use App\Models\Student;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class StudentsCreated extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @return TrendResult
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Student::class);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            90 => __('90 Days'),
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'dashboards-trends-students-created';
    }
}
