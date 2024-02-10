<?php

namespace App\Providers;

use App\Services\ActiveTimeService;
use App\Services\Charts\Types\LineChartService;
use App\Services\StudentService;
use Illuminate\Support\ServiceProvider;

class StudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(StudentService::class, function ($app) {
            return new StudentService(
                $app->make(ActiveTimeService::class),
                $app->make(LineChartService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
