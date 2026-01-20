<?php

namespace App\Models;

use App\Enums\AgeGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'length_in_weeks',
        'min_age',
        'max_age',
    ];

    protected $casts = [
        'length_in_weeks' => 'integer',
        'min_age' => 'integer',
        'max_age' => 'integer',
    ];

    /**
     * Get the course prompts for this course
     */
    public function coursePrompts(): HasMany
    {
        return $this->hasMany(CoursePrompt::class)->orderBy('week_number');
    }

    /**
     * Get the users enrolled in this course
     */
    public function enrolledUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_courses')
            ->withPivot(['started_at', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Get the user course pivot records
     */
    public function userCourses(): HasMany
    {
        return $this->hasMany(UserCourse::class);
    }

    /**
     * Get the total number of weeks in this course
     */
    public function getTotalWeeksAttribute()
    {
        return $this->coursePrompts()->count();
    }

    /**
     * Check if a user is enrolled in this course
     */
    public function isUserEnrolled(User $user): bool
    {
        return $this->enrolledUsers()->where('user_id', $user->id)->exists();
    }

    /**
     * Get enrollment count for this course
     */
    public function getEnrollmentCountAttribute()
    {
        return $this->enrolledUsers()->count();
    }

    /**
     * Get average progress for all enrolled users
     */
    public function getAverageProgressAttribute()
    {
        $totalEnrolled = $this->userCourses()->count();
        if ($totalEnrolled === 0) {
            return 0;
        }

        $completed = $this->userCourses()->whereNotNull('completed_at')->count();

        return round(($completed / $totalEnrolled) * 100, 1);
    }

    /**
     * Get completion rate for this course
     */
    public function getCompletionRateAttribute()
    {
        $totalEnrolled = $this->enrolledUsers()->count();
        if ($totalEnrolled === 0) {
            return 0;
        }

        $completed = $this->userCourses()->whereNotNull('completed_at')->count();

        return round(($completed / $totalEnrolled) * 100, 1);
    }

    /**
     * Get the age group enum for this course
     */
    public function getAgeGroupAttribute(): ?AgeGroup
    {
        if ($this->min_age === null || $this->max_age === null) {
            return null;
        }

        return AgeGroup::fromMinMaxAge($this->min_age, $this->max_age);
    }

    /**
     * Get the age group label for display
     */
    public function getAgeGroupLabelAttribute(): string
    {
        $ageGroup = $this->age_group;

        if ($ageGroup === null) {
            return 'All Ages';
        }

        return $ageGroup->label();
    }

    /**
     * Check if this course is appropriate for a given age
     */
    public function isAppropriateForAge(?int $age): bool
    {
        // Universal courses (no age targeting) are appropriate for everyone
        if ($this->min_age === null && $this->max_age === null) {
            return true;
        }

        // If student has no age set, they can see all courses
        if ($age === null) {
            return true;
        }

        return $age >= $this->min_age && $age <= $this->max_age;
    }

    /**
     * Scope to filter courses for a specific age
     */
    public function scopeForAge(Builder $query, ?int $age): Builder
    {
        // If no age specified, return all courses
        if ($age === null) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($age) {
            // Universal courses (null min_age and max_age)
            $q->where(function (Builder $subQ) {
                $subQ->whereNull('min_age')->whereNull('max_age');
            })
            // Or courses where the age falls within the range
            ->orWhere(function (Builder $subQ) use ($age) {
                $subQ->where('min_age', '<=', $age)
                    ->where('max_age', '>=', $age);
            });
        });
    }
}
