<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\CategoriesGrade;
use App\Models\Course;
use App\Services\Courses\CourseService;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Teachers/Courses/Index', [
            'courses' => Course::paginate(12),
            'categories' => CategoriesGrade::all()->pluck('category')->sort()->all(),
            'lengths' => $this->courseService->getLengths(),
            'levels' => $this->courseService->getLevels(),
        ]);
    }

    public function show(Course $course): Response
    {
        return Inertia::render('Teachers/Courses/Show', [
            'course' => $course->load(['categoryGrade', 'coursePrompts']),
        ]);
    }
}
