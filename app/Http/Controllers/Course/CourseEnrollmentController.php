<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Services\Courses\CourseService;
use Response;

class CourseEnrollmentController extends Controller
{
    public function __construct(private readonly CourseService $courseService)
    {
    }

    public function __invoke(Course $course, User $user)
    {
        $this->courseService->enrollStudent($course, $user);

        return Response::json(['success' => true]);
    }
}
