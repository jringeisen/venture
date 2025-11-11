<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'length_in_weeks',
    ];

    protected $casts = [
        'length_in_weeks' => 'integer',
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
                    ->withPivot(['enrolled_at', 'completed_at', 'current_week', 'progress_percentage'])
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
        return $this->userCourses()->avg('progress_percentage') ?? 0;
    }

    /**
     * Get completion rate for this course
     */
    public function getCompletionRateAttribute()
    {
        $totalEnrolled = $this->enrolledUsers()->count();
        if ($totalEnrolled === 0) return 0;
        
        $completed = $this->userCourses()->whereNotNull('completed_at')->count();
        return round(($completed / $totalEnrolled) * 100, 1);
    }
}