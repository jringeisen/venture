<?php

namespace App\Services\Courses;

use App\Models\CategoriesGrade;
use App\Models\Course;
use App\Models\User;
use App\Models\UserCourse;

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

    public function enrollStudent(Course $course, User $student): UserCourse
    {
        $exists = UserCourse::where('course_id', $course->id)->where('user_id', $student->id)->first();

        if (!$exists) {
            $now = now();

            $userCourse = new UserCourse();

            $userCourse->course_id = $course->id;
            $userCourse->user_id = $student->id;

            $userCourse->created_at = $now;
            $userCourse->updated_at = $now;

            $userCourse->save();

            return $userCourse;
        }

        return $exists;
    }
}
