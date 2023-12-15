<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_category',
        'content',
        'questions',
        'outline',
    ];

    public function promptQuestion(): BelongsTo
    {
        return $this->belongsTo(PromptQuestion::class);
    }
}
