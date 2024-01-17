<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Nova\Auth\Impersonatable;

class Student extends Authenticatable
{
    use HasFactory, Impersonatable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'grade',
        'age',
        'motivational_message',
        'current_streak',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'name' => 'string',
        'username' => 'string',
        'grade' => 'integer',
        'age' => 'integer',
        'motivational_message' => 'datetime',
        'current_streak' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promptQuestions(): HasMany
    {
        return $this->hasMany(PromptQuestion::class);
    }

    public function promptAnswers(): HasManyThrough
    {
        return $this->hasManyThrough(PromptAnswer::class, PromptQuestion::class);
    }

    protected function timezone(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?? config('app.timezone')
        );
    }

    public function canAskQuestions(): bool
    {
        if ($this->user->subscribed()) {
            return true;
        }

        if (! $this->user->subscribed() && $this->user->total_questions_asked < config('app.student_free_question_count')) {
            return true;
        }

        return false;
    }
}
