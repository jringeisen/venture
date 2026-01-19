<?php

namespace App\Nova;

use App\Nova\Repeater\TriviaQuestion;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class CoursePrompt extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\CoursePrompt>
     */
    public static $model = \App\Models\CoursePrompt::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'description',
    ];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Courses';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Course Weeks';
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Course Week';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Course')
                ->sortable()
                ->rules('required'),

            Number::make('Week Number', 'week_number')
                ->sortable()
                ->rules('required', 'integer', 'min:1'),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->rules('nullable')
                ->hideFromIndex(),

            Trix::make('Content')
                ->rules('nullable')
                ->alwaysShow()
                ->help('The main lesson content for this week'),

            Number::make('Duration (minutes)', 'estimated_duration_minutes')
                ->rules('nullable', 'integer', 'min:1')
                ->hideFromIndex(),

            Panel::make('Learning Objectives', [
                Code::make('Learning Objectives', 'learning_objectives')
                    ->json()
                    ->rules('nullable')
                    ->help('JSON array of learning objectives, e.g. ["Understand X", "Learn Y"]'),
            ]),

            Repeater::make('Trivia Questions', 'trivia_questions')
                ->repeatables([
                    TriviaQuestion::make(),
                ])
                ->asJson(),

            Panel::make('Additional Resources', [
                Code::make('Additional Resources', 'additional_resources')
                    ->json()
                    ->rules('nullable')
                    ->help('JSON array of additional resources, e.g. [{"title": "...", "url": "..."}]'),
            ]),
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
