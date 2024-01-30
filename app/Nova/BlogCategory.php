<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class BlogCategory extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\BlogCategory>
     */
    public static $model = \App\Models\BlogCategory::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'slug', 'description', 'meta_title', 'meta_description',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Slug::make('Slug')
                ->from('Name')
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->sortable()
                ->rules('required', 'max:1000'),

            Text::make('Meta Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Meta Description')
                ->sortable()
                ->rules('required', 'max:1500'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
