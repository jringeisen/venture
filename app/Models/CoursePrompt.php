<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePrompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'week_number',
        'title',
        'description',
        'prompt_text',
        'learning_objectives',
        'trivia_questions',
        'additional_resources',
        'estimated_duration_minutes',
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'trivia_questions' => 'array',
        'additional_resources' => 'array',
        'week_number' => 'integer',
        'estimated_duration_minutes' => 'integer',
    ];

    /**
     * Get the course that owns this prompt
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope for ordering by week number
     */
    public function scopeOrderedByWeek($query)
    {
        return $query->orderBy('week_number');
    }

    /**
     * Scope for getting a specific week
     */
    public function scopeWeek($query, $weekNumber)
    {
        return $query->where('week_number', $weekNumber);
    }

    /**
     * Get the next week's prompt
     */
    public function getNextWeekAttribute()
    {
        return static::where('course_id', $this->course_id)
                    ->where('week_number', '>', $this->week_number)
                    ->orderBy('week_number')
                    ->first();
    }

    /**
     * Get the previous week's prompt
     */
    public function getPreviousWeekAttribute()
    {
        return static::where('course_id', $this->course_id)
                    ->where('week_number', '<', $this->week_number)
                    ->orderBy('week_number', 'desc')
                    ->first();
    }

    /**
     * Check if this is the first week
     */
    public function getIsFirstWeekAttribute()
    {
        return $this->week_number === 1;
    }

    /**
     * Check if this is the last week
     */
    public function getIsLastWeekAttribute()
    {
        return !$this->nextWeek;
    }

    /**
     * Get formatted week title
     */
    public function getFormattedTitleAttribute()
    {
        return "Week {$this->week_number}: {$this->title}";
    }

    /**
     * Generate AI prompt for content generation
     */
    public function generateAIPrompt($userAge = null): string
    {
        $ageContext = $userAge ? "for a {$userAge}-year-old student" : "";
        
        return "You are teaching {$this->formatted_title} as part of a {$this->course->title} course {$ageContext}. 
                
                Learning objectives for this week:
                " . implode("\n", $this->learning_objectives ?? []) . "
                
                Base prompt: {$this->prompt_text}
                
                Please provide comprehensive, engaging content that covers these objectives while being age-appropriate and interactive.";
    }
}