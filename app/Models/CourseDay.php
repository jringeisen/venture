<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseDay extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_prompt_id',
        'day_number',
        'title',
        'description',
        'content',
        'learning_objectives',
        'trivia_questions',
        'estimated_duration_minutes',
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'trivia_questions' => 'array',
        'day_number' => 'integer',
        'estimated_duration_minutes' => 'integer',
    ];

    /**
     * Get the week (course prompt) that this day belongs to
     */
    public function week(): BelongsTo
    {
        return $this->belongsTo(CoursePrompt::class, 'course_prompt_id');
    }

    /**
     * Alias for week relationship
     */
    public function coursePrompt(): BelongsTo
    {
        return $this->week();
    }

    /**
     * Get trivia questions formatted for the frontend.
     */
    public function getTriviaQuestionsForFrontend(): ?array
    {
        $questions = $this->trivia_questions;

        if (! is_array($questions)) {
            return null;
        }

        return array_map(function ($question) {
            // If already in the correct format (has 'options' array), return as-is
            if (isset($question['options']) && is_array($question['options'])) {
                return $question;
            }

            // Transform from flat format (option_a, option_b, etc.)
            $fields = $question['fields'] ?? $question;

            return [
                'question' => $fields['question'] ?? '',
                'options' => [
                    $fields['option_a'] ?? '',
                    $fields['option_b'] ?? '',
                    $fields['option_c'] ?? '',
                    $fields['option_d'] ?? '',
                ],
                'correct_answer' => (int) ($fields['correct_answer'] ?? 0),
            ];
        }, $questions);
    }

    /**
     * Scope for ordering by day number
     */
    public function scopeOrderedByDay($query)
    {
        return $query->orderBy('day_number');
    }

    /**
     * Scope for getting a specific day
     */
    public function scopeDay($query, $dayNumber)
    {
        return $query->where('day_number', $dayNumber);
    }

    /**
     * Get the next day within the same week
     */
    public function getNextDayAttribute()
    {
        return static::where('course_prompt_id', $this->course_prompt_id)
            ->where('day_number', '>', $this->day_number)
            ->orderBy('day_number')
            ->first();
    }

    /**
     * Get the previous day within the same week
     */
    public function getPreviousDayAttribute()
    {
        return static::where('course_prompt_id', $this->course_prompt_id)
            ->where('day_number', '<', $this->day_number)
            ->orderBy('day_number', 'desc')
            ->first();
    }

    /**
     * Check if this is the first day of the week
     */
    public function getIsFirstDayAttribute()
    {
        return $this->day_number === 1;
    }

    /**
     * Check if this is the last day of the week
     */
    public function getIsLastDayAttribute()
    {
        return ! $this->nextDay;
    }

    /**
     * Get formatted day title
     */
    public function getFormattedTitleAttribute()
    {
        return "Day {$this->day_number}: {$this->title}";
    }
}
