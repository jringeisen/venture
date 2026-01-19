<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'current_week',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this enrollment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course for this enrollment
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if the course is completed
     */
    public function getIsCompletedAttribute()
    {
        return ! is_null($this->completed_at);
    }

    /**
     * Get days since enrollment
     */
    public function getDaysSinceEnrollmentAttribute()
    {
        return $this->started_at ? $this->started_at->diffInDays(now()) : 0;
    }

    /**
     * Mark course as completed
     */
    public function markCompleted(): void
    {
        $this->update(['completed_at' => now()]);
    }

    /**
     * Advance to the next week
     */
    public function advanceToNextWeek(): bool
    {
        $totalWeeks = $this->course->length_in_weeks ?? $this->course->coursePrompts()->count();

        if ($this->current_week >= $totalWeeks) {
            return false;
        }

        $this->increment('current_week');

        return true;
    }

    /**
     * Check if user can access a specific week
     */
    public function canAccessWeek(int $week): bool
    {
        return $week <= $this->current_week;
    }

    /**
     * Check if this is the last week
     */
    public function isLastWeek(): bool
    {
        $totalWeeks = $this->course->length_in_weeks ?? $this->course->coursePrompts()->count();

        return $this->current_week >= $totalWeeks;
    }

    /**
     * Scope for active enrollments (not completed)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('completed_at');
    }

    /**
     * Scope for completed enrollments
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }
}
