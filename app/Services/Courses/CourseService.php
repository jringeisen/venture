<?php

namespace App\Services\Courses;

use App\Models\CategoriesGrade;
use App\Models\Course;

class CourseService
{
    public function getLengths(): array
    {
        return Course::all()
            ->keyBy('length_in_weeks')
            ->keys()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    public function getLevels(): array
    {
        return CategoriesGrade::all()
            ->keyBy('grade')
            ->keys()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
