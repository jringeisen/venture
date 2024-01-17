<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\Dashboards\Trends\PromptQuestionsCreated;
use App\Nova\Metrics\Dashboards\Trends\StudentsCreated;
use App\Nova\Metrics\Dashboards\Trends\UsersCreated;
use Laravel\Nova\Dashboard;

class CreatedResources extends Dashboard
{
    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public function label()
    {
        return 'Created Resources';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new UsersCreated)->width('1/3'),
            (new StudentsCreated)->width('1/3'),
            (new PromptQuestionsCreated)->width('1/3'),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'created-resources';
    }
}
