<?php

namespace App\Models;

use App\Enums\CourseLevels;
use App\Enums\CourseSubjects;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            get: function (?string $value) {
                if (Str::contains($value, ['pexels'])) {
                    return $value;
                }

                return $value ? Storage::temporaryUrl($value, now()->addMinutes(5)) : null;
            },
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
