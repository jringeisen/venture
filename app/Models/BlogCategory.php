<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
    ];

    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function scopeHasPublishedPosts(Builder $query): Builder
    {
        return $query->whereHas('posts', function ($query) {
            $query->where('is_published', true);
        });
    }
}
