<?php

namespace App\Nova;

use App\Enums\FeedbackStatuses;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Feedback extends Resource
{
    public static $model = \App\Models\Feedback::class;

    public static $title = 'id';

    public static $search = [
        'id', 'title', 'description', 'status',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->sortable()
                ->rules('required', 'max:5000'),

            Select::make('Status')
                ->options(FeedbackStatuses::toSelectArray())
                ->rules('required')
                ->default('open')
                ->sortable(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
