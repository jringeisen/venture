<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'grade',
        'age',
        'is_temporary_password',
        'motivational_message',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'grade' => 'integer',
        'age' => 'integer',
        'is_temporary_password' => 'boolean',
        'motivational_message' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promptQuestions()
    {
        return $this->hasMany(PromptQuestion::class);
    }

    public function promptAnswers()
    {
        return $this->hasManyThrough(PromptAnswer::class, PromptQuestion::class);
    }
}
