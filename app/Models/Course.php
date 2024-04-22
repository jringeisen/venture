<?php

namespace App\Models;

use App\Enums\CourseLevels;
use App\Enums\CourseSubjects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'length',
        'image',
        'creators_name',
        'level',
        'subject',
    ];

    protected $casts = [
        'level' => CourseLevels::class,
        'subject' => CourseSubjects::class,
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value
                ? Storage::temporaryUrl($value, now()->addMinutes(5))
                : null,
        );
    }
}
