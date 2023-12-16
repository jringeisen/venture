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
            $startOfDayUtc = Carbon::parse($date)->startOfDay()->timezone(auth()->user()->timezone);
            $endOfDayUtc = Carbon::parse($date)->endOfDay()->timezone(auth()->user()->timezone);

            $query->whereBetween('created_at', [$startOfDayUtc, $endOfDayUtc]);
        });
    }
}
