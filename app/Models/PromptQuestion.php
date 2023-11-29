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

    public function scopeFilterByDate(Builder $query, Request $request): Builder
    {
        return $query->when($request->date, function ($query) use ($request) {
            $query->whereDate('created_at', $request->date);
        });
    }
}
