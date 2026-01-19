<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TriviaQuestion extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make('Question')
                ->rules('required', 'max:500')
                ->help('The trivia question to ask'),

            Text::make('Option A', 'option_a')
                ->rules('required', 'max:255'),

            Text::make('Option B', 'option_b')
                ->rules('required', 'max:255'),

            Text::make('Option C', 'option_c')
                ->rules('required', 'max:255'),

            Text::make('Option D', 'option_d')
                ->rules('required', 'max:255'),

            Select::make('Correct Answer', 'correct_answer')
                ->options([
                    0 => 'Option A',
                    1 => 'Option B',
                    2 => 'Option C',
                    3 => 'Option D',
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->help('Select which option is the correct answer'),
        ];
    }
}
