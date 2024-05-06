<?php

namespace App\Http\Controllers\Student\Courses;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnrollController extends Controller
{
    public function __invoke(Request $request, Course $course)
    {
        $course->users()->attach($request->user()->id);

        return to_route('student.courses.show', [
            'course' => $course,
        ]);
    }
}
