<?php

namespace App\Nova\Metrics\Dashboards\Trends;

use App\Models\PromptQuestion;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class PromptQuestionsCreated extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @return TrendResult
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, PromptQuestion::class);
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
     * Determine for how many minutes the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'dashboards-trends-prompt-questions-created';
    }
}
