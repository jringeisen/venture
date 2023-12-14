<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            $query->whereBetween('created_at', [
                Carbon::createFromFormat('Y-m-d', $date, auth()->user()->timezone)->startOfDay()->setTimezone('UTC'),
                Carbon::createFromFormat('Y-m-d', $date, auth()->user()->timezone)->endOfDay()->setTimezone('UTC'),
            ]);
        });
    }
}
