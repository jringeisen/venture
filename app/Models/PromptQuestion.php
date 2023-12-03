<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promptAnswer()
    {
        return $this->hasOne(PromptAnswer::class);
    }

    public function scopeFilterByDate(Builder $query, string $date): Builder
    {
        return $query->when($date, function ($query) use ($date) {
            $startOfDayInUserTimezone = Carbon::parse($date)->startOfDay();
            $endOfDayInUserTimezone = Carbon::parse($date)->endOfDay();

            $query->whereBetween('created_at', [$startOfDayInUserTimezone, $endOfDayInUserTimezone]);
        });
    }
}
