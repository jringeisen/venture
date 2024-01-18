<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function wordCount(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format(str_word_count($this->content)),
        );
    }

    protected function subjectCategory(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? $value : 'Not Categorized',
        );
    }
}
