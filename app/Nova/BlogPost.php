<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class BlogPost extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\BlogPost>
     */
    public static $model = \App\Models\BlogPost::class;

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
        'id', 'title', 'slug', 'description', 'meta_title', 'meta_description',
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

            BelongsTo::make('User')
                ->default($request->user()->id)
                ->nullable(),

            BelongsTo::make('Category', 'category', BlogCategory::class)->nullable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Slug::make('Slug')
                ->from('Title')
                ->rules('required', 'max:255')
                ->hideFromIndex(),

            Markdown::make('Content')
                ->sortable()
                ->rules('required'),

            Textarea::make('Excerpt')
                ->sortable()
                ->rules('required', 'max:2000'),

            Boolean::make('Is Published'),

            Panel::make('Featured Image', $this->featuredImageFields()),

            Panel::make('SEO Fields', $this->seoFields()),
        ];
    }

    protected function featuredImageFields()
    {
        return [
            Image::make('Featured Image')
                ->path('/blog-posts/images')
                ->nullable()
                ->disableDownload()
                ->prunable()
                ->thumbnail(function ($value, $disk) {
                    return $value;
                })
                ->preview(function ($value, $disk) {
                    return $value;
                }),

            Text::make('Alt Text')
                ->rules('max:255')
                ->hideFromIndex(),
        ];
    }

    protected function seoFields()
    {
        return [
            Text::make('Meta Title')
                ->sortable()
                ->nullable()
                ->rules('max:255')
                ->hideFromIndex(),

            Textarea::make('Meta Description')
                ->sortable()
                ->nullable()
                ->rules('max:1500'),
        ];
    }
}
