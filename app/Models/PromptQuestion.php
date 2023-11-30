<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PromptQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
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
            $query->whereDate('created_at', $date);
        });
    }
}
