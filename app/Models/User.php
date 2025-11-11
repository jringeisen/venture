<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Nova\Auth\Impersonatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, HasApiTokens, HasFactory, Impersonatable, Notifiable;

    protected $fillable = [
        'parent_id',
        'name',
        'email',
        'username',
        'grade',
        'age',
        'motivational_message',
        'current_streak',
        'password',
        'timezone',
        'total_questions_asked',
        'email_verified_at',
        'referred_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'name' => 'string',
        'username' => 'string',
        'grade' => 'integer',
        'age' => 'integer',
        'motivational_message' => 'datetime',
        'current_streak' => 'integer',
        'email_verified_at' => 'datetime',
        'total_questions_asked' => 'integer',
        'referred_by' => 'string',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function promptQuestions(): HasMany
    {
        return $this->hasMany(PromptQuestion::class);
    }

    public function promptAnswers(): HasManyThrough
    {
        return $this->hasManyThrough(PromptAnswer::class, PromptQuestion::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    protected function timezone(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?? config('app.timezone')
        );
    }

    public function activeTime(): HasMany
    {
        return $this->hasMany(ActiveTime::class);
    }

    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function isStudent(): bool
    {
        return ! is_null($this->parent_id);
    }

    /**
     * Get the courses this user is enrolled in
     */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'user_courses')
                    ->withPivot(['enrolled_at', 'completed_at', 'current_week', 'progress_percentage'])
                    ->withTimestamps();
    }

    /**
     * Get the user course enrollments
     */
    public function userCourses(): HasMany
    {
        return $this->hasMany(UserCourse::class);
    }

    /**
     * Get active (non-completed) course enrollments
     */
    public function activeCourses(): HasMany
    {
        return $this->hasMany(UserCourse::class)->active();
    }

    /**
     * Get completed course enrollments
     */
    public function completedCourses(): HasMany
    {
        return $this->hasMany(UserCourse::class)->completed();
    }

    /**
     * Check if user is enrolled in a specific course
     */
    public function isEnrolledInCourse(Course $course): bool
    {
        return $this->enrolledCourses()->where('course_id', $course->id)->exists();
    }

    /**
     * Enroll user in a course
     */
    public function enrollInCourse(Course $course): UserCourse
    {
        if ($this->isEnrolledInCourse($course)) {
            throw new \Exception('User is already enrolled in this course');
        }

        return $this->userCourses()->create([
            'course_id' => $course->id,
            'started_at' => now(),
        ]);
    }

    /**
     * Get total courses completed
     */
    public function getTotalCoursesCompletedAttribute(): int
    {
        return $this->completedCourses()->count();
    }

    /**
     * Get total time spent on courses
     */
    public function getTotalCourseTimeAttribute(): int
    {
        return 0; // Simplified since time tracking columns don't exist
    }

    /**
     * Get average course progress
     */
    public function getAverageCourseProgressAttribute(): float
    {
        return 0; // Simplified since progress columns don't exist
    }
}
