<?php

namespace App\Providers;

use App\Nova\BlogCategory;
use App\Nova\BlogPost;
use App\Nova\CategoriesGrade;
use App\Nova\Course;
use App\Nova\CoursePrompt;
use App\Nova\Dashboards\CreatedResources;
use App\Nova\Dashboards\Main;
use App\Nova\Feedback;
use App\Nova\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::mainMenu(function () {
            return [
                MenuSection::make('Dashboards', [
                    MenuItem::dashboard(Main::class),
                    MenuItem::dashboard(CreatedResources::class),
                ])->icon('chart-bar')->collapsable(),

                MenuSection::make('Blog Posts', [
                    MenuItem::resource(BlogCategory::class),
                    MenuItem::resource(BlogPost::class),
                ])->icon('book-open')->collapsable(),

                MenuSection::make('Courses', [
                    MenuItem::resource(Course::class),
                    MenuItem::resource(CategoriesGrade::class),
                    MenuItem::resource(CoursePrompt::class),
                ])->icon('academic-cap')->collapsable(),

                MenuSection::resource(Feedback::class)->icon('mail'),
                MenuSection::resource(User::class)->icon('users'),
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, config('app.nova_admin_emails'));
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new Main,
            new CreatedResources,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
