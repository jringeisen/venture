<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiveTime extends Model
{
    use HasFactory;

    protected $table = 'active_time';

    protected $appends = ['total_minutes'];

    protected $fillable = [
        'total_seconds',
        'user_id',
        'date',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function getTotalMinutesAttribute(): int
    {
        return floor($this->total_seconds / 60);
    }

    public function scopeFilterByTimeframe(Builder $query, string $timeframe): Builder
    {
        return $query->when($timeframe, function (Builder $query) use ($timeframe) {
            $usersTimezone = request()->user()->timezone;

            $query->when($timeframe === 'yearly', function (Builder $query) use ($usersTimezone) {
                $query->whereYear('active_time.date', now($usersTimezone)->year);
            })
                ->when($timeframe === 'monthly', function (Builder $query) use ($usersTimezone) {
                    $query->whereBetween('active_time.date', [
                        now($usersTimezone)->startOfMonth(),
                        now($usersTimezone)->endOfMonth(),
                    ]);
                })
                ->when($timeframe === 'weekly', function (Builder $query) use ($usersTimezone) {
                    $query->whereBetween('active_time.date', [
                        now($usersTimezone)->startOfWeek(),
                        now($usersTimezone)->endOfWeek(),
                    ]);
                });
        });
    }
}
