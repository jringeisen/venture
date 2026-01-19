<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'week_number',
        'started_at',
        'ended_at',
        'duration_seconds',
        'is_active',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user for this session
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course for this session
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Start a new learning session
     */
    public static function startSession(int $userId, int $courseId, int $weekNumber): self
    {
        // End any active sessions for this user/course
        static::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'ended_at' => now(),
            ]);

        return static::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'week_number' => $weekNumber,
            'started_at' => now(),
            'is_active' => true,
        ]);
    }

    /**
     * Update session duration
     */
    public function updateDuration(int $seconds): void
    {
        $this->update([
            'duration_seconds' => $seconds,
        ]);
    }

    /**
     * End the session
     */
    public function endSession(): void
    {
        $this->update([
            'is_active' => false,
            'ended_at' => now(),
        ]);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        if ($minutes > 60) {
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m {$seconds}s";
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for a specific user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for a specific course
     */
    public function scopeForCourse($query, int $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Get total time spent by user on a course
     */
    public static function getTotalTimeForCourse(int $userId, int $courseId): int
    {
        return static::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->sum('duration_seconds');
    }

    /**
     * Get total time spent by user on a specific week
     */
    public static function getTotalTimeForWeek(int $userId, int $courseId, int $weekNumber): int
    {
        return static::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('week_number', $weekNumber)
            ->sum('duration_seconds');
    }
}
