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
        'last_accessed_at',
        'time_spent_minutes',
        'trivia_scores',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'trivia_scores' => 'array',
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

    /**
     * Record trivia score for a specific week
     */
    public function recordTriviaScore(int $week, int $score): void
    {
        $scores = $this->trivia_scores ?? [];
        $scores["week_{$week}"] = [
            'score' => $score,
            'recorded_at' => now()->toIso8601String(),
        ];

        $this->update(['trivia_scores' => $scores]);
    }

    /**
     * Get trivia score for a specific week
     */
    public function getTriviaScore(int $week): ?int
    {
        $scores = $this->trivia_scores ?? [];

        return $scores["week_{$week}"]['score'] ?? null;
    }

    /**
     * Add time spent learning (in minutes)
     */
    public function addTimeSpent(int $minutes): void
    {
        $this->increment('time_spent_minutes', $minutes);
    }

    /**
     * Update last accessed timestamp
     */
    public function updateLastAccessed(): void
    {
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Get formatted time spent
     */
    public function getFormattedTimeSpentAttribute(): string
    {
        $hours = floor($this->time_spent_minutes / 60);
        $minutes = $this->time_spent_minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Calculate progress percentage
     */
    public function getProgressAttribute(): int
    {
        $totalWeeks = $this->course->length_in_weeks ?? $this->course->coursePrompts()->count() ?? 1;

        if ($this->is_completed) {
            return 100;
        }

        // current_week represents the week user is ON, so completed weeks = current_week - 1
        // But if they've started week 1, they're making progress, so we use current_week
        return min(100, (int) round(($this->current_week / $totalWeeks) * 100));
    }
}
