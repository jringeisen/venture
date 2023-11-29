<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_category',
        'content',
        'questions',
        'outline',
    ];

    public function promptQuestion()
    {
        return $this->belongsTo(PromptQuestion::class);
    }
}
