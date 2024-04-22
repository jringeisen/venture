<?php

namespace App\Nova;

use App\Enums\CourseLevels;
use Laravel\Nova\Fields\ID;
use App\Enums\CourseLengths;
use Illuminate\Http\Request;
use App\Enums\CourseSubjects;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Course extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Course>
     */
    public static $model = \App\Models\Course::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Slug::make('Slug')
                ->from('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->sortable()
                ->rules('required',  'max:15000'),

            Select::make('Length')
                ->options(CourseLengths::toSelectArray())
                ->sortable()
                ->rules('required')
                ->displayUsingLabels(),

            Image::make('Image')
                ->disk('s3')
                ->path('/courses/images')
                ->nullable()
                ->disableDownload()
                ->prunable()
                ->thumbnail(function ($value, $disk) {
                    return $value;
                })
                ->preview(function ($value, $disk) {
                    return $value;
                }),

            Text::make('Creators Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('Level')
                ->options(CourseLevels::toSelectArray())
                ->sortable()
                ->rules('required')
                ->displayUsingLabels(),

            Select::make('Subject')
                ->options(CourseSubjects::toSelectArray())
                ->sortable()
                ->rules('required')
                ->displayUsingLabels(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
