<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'grade',
        'age',
        'is_temporary_password'
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
