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
        'current_day',
        'started_at',
        'completed_at',
        'last_accessed_at',
        'time_spent_minutes',
        'week_times',
        'trivia_scores',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'trivia_scores' => 'array',
        'week_times' => 'array',
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
        $this->update(['current_day' => 1]); // Reset day to 1 when advancing to next week

        return true;
    }

    /**
     * Advance to the next day within the current week
     */
    public function advanceToNextDay(): bool
    {
        $currentWeekPrompt = $this->course->coursePrompts()
            ->where('week_number', $this->current_week)
            ->first();

        if (! $currentWeekPrompt) {
            return false;
        }

        $totalDays = $currentWeekPrompt->days_count ?? $currentWeekPrompt->days()->count();

        if ($this->current_day >= $totalDays) {
            return false; // At the last day, need to advance week instead
        }

        $this->increment('current_day');

        return true;
    }

    /**
     * Check if this is the last day of the current week
     */
    public function isLastDayOfWeek(): bool
    {
        $currentWeekPrompt = $this->course->coursePrompts()
            ->where('week_number', $this->current_week)
            ->first();

        if (! $currentWeekPrompt) {
            return true;
        }

        $totalDays = $currentWeekPrompt->days_count ?? $currentWeekPrompt->days()->count();

        return $this->current_day >= $totalDays;
    }

    /**
     * Check if user can access a specific week
     */
    public function canAccessWeek(int $week): bool
    {
        return $week <= $this->current_week;
    }

    /**
     * Check if user can access a specific day within a week
     */
    public function canAccessDay(int $week, int $day): bool
    {
        // Can access any day in previous weeks
        if ($week < $this->current_week) {
            return true;
        }

        // Can only access up to current day in current week
        if ($week === $this->current_week) {
            return $day <= $this->current_day;
        }

        // Cannot access future weeks
        return false;
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
     * Record trivia score for a specific day within a week
     */
    public function recordDayTriviaScore(int $week, int $day, int $score): void
    {
        $scores = $this->trivia_scores ?? [];
        $scores["week_{$week}_day_{$day}"] = [
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
     * Get trivia score for a specific day within a week
     */
    public function getDayTriviaScore(int $week, int $day): ?int
    {
        $scores = $this->trivia_scores ?? [];

        return $scores["week_{$week}_day_{$day}"]['score'] ?? null;
    }

    /**
     * Add time spent learning (in minutes)
     */
    public function addTimeSpent(int $minutes): void
    {
        $this->increment('time_spent_minutes', $minutes);
    }

    /**
     * Add time spent on a specific week (in seconds)
     */
    public function addWeekTime(int $week, int $seconds): void
    {
        $weekTimes = $this->week_times ?? [];
        $key = "week_{$week}";
        $weekTimes[$key] = ($weekTimes[$key] ?? 0) + $seconds;

        $this->update(['week_times' => $weekTimes]);

        // Also update total time spent (convert seconds to minutes)
        $this->increment('time_spent_minutes', (int) floor($seconds / 60));
    }

    /**
     * Add time spent on a specific day within a week (in seconds)
     */
    public function addDayTime(int $week, int $day, int $seconds): void
    {
        $weekTimes = $this->week_times ?? [];
        $dayKey = "week_{$week}_day_{$day}";
        $weekKey = "week_{$week}";

        // Track day-level time
        $weekTimes[$dayKey] = ($weekTimes[$dayKey] ?? 0) + $seconds;
        // Also aggregate to week-level time
        $weekTimes[$weekKey] = ($weekTimes[$weekKey] ?? 0) + $seconds;

        $this->update(['week_times' => $weekTimes]);

        // Also update total time spent (convert seconds to minutes)
        $this->increment('time_spent_minutes', (int) floor($seconds / 60));
    }

    /**
     * Get time spent on a specific week (in seconds)
     */
    public function getWeekTime(int $week): int
    {
        $weekTimes = $this->week_times ?? [];
        return $weekTimes["week_{$week}"] ?? 0;
    }

    /**
     * Get time spent on a specific day within a week (in seconds)
     */
    public function getDayTime(int $week, int $day): int
    {
        $weekTimes = $this->week_times ?? [];
        return $weekTimes["week_{$week}_day_{$day}"] ?? 0;
    }

    /**
     * Get formatted time for a specific week
     */
    public function getFormattedWeekTime(int $week): string
    {
        $seconds = $this->getWeekTime($week);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$remainingMinutes}m";
        }

        return "{$minutes}m";
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
