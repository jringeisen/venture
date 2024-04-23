<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::when(request('search'), function ($query, $search) {
            $query->where('title', 'like', '%'.$search.'%')
                ->orWhere('slug', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%');
        })->get();

        return Inertia::render('Student/Courses/Index', [
            'courses' => $courses,
            'search' => request('search', ''),
            'subjects' => $courses->unique('subject')->map(function ($course) use ($courses) {
                return [
                    'text' => $course->subject,
                    'count' => $courses->where('subject', $course->subject)->count(),
                ];
            }),
        ]);
    }
}
