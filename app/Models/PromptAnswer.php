<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_category',
        'content',
        'word_count',
        'questions',
        'outline',
    ];

    public function promptQuestion(): BelongsTo
    {
        return $this->belongsTo(PromptQuestion::class);
    }

    protected function subjectCategory(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? $value : 'Not Categorized',
        );
    }

    public function scopeFilterByTimeframe(Builder $query, string $timeframe): Builder
    {
        return $query->when($timeframe, function (Builder $query) use ($timeframe) {
            $usersTimezone = request()->user()->timezone;

            $query->when($timeframe === 'yearly', function (Builder $query) use ($usersTimezone) {
                $query->whereYear('prompt_answers.created_at', now($usersTimezone)->year);
            })
                ->when($timeframe === 'monthly', function (Builder $query) use ($usersTimezone) {
                    $query->whereBetween('prompt_answers.created_at', [
                        now($usersTimezone)->startOfMonth(),
                        now($usersTimezone)->endOfMonth(),
                    ]);
                })
                ->when($timeframe === 'weekly', function (Builder $query) use ($usersTimezone) {
                    $query->whereBetween('prompt_answers.created_at', [
                        now($usersTimezone)->startOfWeek(),
                        now($usersTimezone)->endOfWeek(),
                    ]);
                });
        });
    }
}
