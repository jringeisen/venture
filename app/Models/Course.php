<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    public function categoryGrade(): BelongsTo
    {
        return $this->belongsTo(CategoriesGrade::class, 'categories_grade_id', 'id');
    }
}
