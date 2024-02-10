<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PromptQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'total_tokens',
    ];

    protected $casts = [
        'total_tokens' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promptAnswer(): HasOne
    {
        return $this->hasOne(PromptAnswer::class);
    }

    public function scopeFilterByDate(Builder $query, string $date): Builder
    {
        return $query->when($date, function (Builder $query) use ($date) {
            $usersTimezone = auth()->user()->timezone;

            $startOfDay = Carbon::parse($date)->timezone($usersTimezone)->startOfDay()->utc();
            $endOfDay = Carbon::parse($date)->timezone($usersTimezone)->endOfDay()->utc();

            $query->whereBetween('prompt_questions.created_at', [$startOfDay, $endOfDay]);
        });
    }

    public function scopeFilterByTimeframe(Builder $query, string $timeframe): Builder
    {
        return $query->when($timeframe, function (Builder $query) use ($timeframe) {
            $usersTimezone = request()->user()->timezone;

            $query->when($timeframe === 'yearly', function (Builder $query) use ($usersTimezone) {
                $query->whereYear('prompt_questions.created_at', now($usersTimezone)->year);
            })
                ->when($timeframe === 'monthly', function (Builder $query) use ($usersTimezone) {
                    $query->whereBetween('prompt_questions.created_at', [
                        now($usersTimezone)->startOfMonth(),
                        now($usersTimezone)->endOfMonth(),
                    ]);
                })
                ->when($timeframe === 'weekly', function (Builder $query) use ($usersTimezone) {
                    $query->whereBetween('prompt_questions.created_at', [
                        now($usersTimezone)->startOfWeek(),
                        now($usersTimezone)->endOfWeek(),
                    ]);
                });
        });
    }
}
