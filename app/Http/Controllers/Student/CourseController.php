<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function __construct(
        private CourseService $courseService
    ) {}

    /**
     * Display course catalog
     */
    public function index(Request $request): Response
    {
        $courses = $this->courseService->getPaginatedCourses(
            perPage: 12,
            subject: $request->get('subject')
        );

        $subjects = $this->courseService->getCourseSubjectsWithCounts();
        $userEnrolledCourses = $this->courseService->getUserEnrolledCourses($request->user());
        $recommendedCourses = $this->courseService->getRecommendedCourses($request->user());

        return Inertia::render('Student/Courses/Index', [
            'courses' => $courses,
            'subjects' => $subjects,
            'enrolledCourses' => $userEnrolledCourses,
            'recommendedCourses' => $recommendedCourses,
            'selectedSubject' => $request->get('subject'),
        ]);
    }

    /**
     * Display a specific course
     */
    public function show(Request $request, Course $course): Response
    {
        $course->load(['coursePrompts' => function ($query) {
            $query->orderBy('week_number');
        }]);

        $userProgress = $this->courseService->getUserCourseProgress($request->user(), $course);
        $statistics = $this->courseService->getCourseStatistics($course);
        $isEnrolled = $request->user()->isEnrolledInCourse($course);

        return Inertia::render('Student/Courses/Show', [
            'course' => $course,
            'userProgress' => $userProgress,
            'statistics' => $statistics,
            'isEnrolled' => $isEnrolled,
        ]);
    }

    /**
     * Enroll user in a course
     */
    public function enroll(Request $request, Course $course)
    {
        try {
            $enrollment = $this->courseService->enrollUser($request->user(), $course);

            return redirect()
                ->route('student.courses.learn', ['course' => $course->id, 'week' => 1])
                ->with('success', 'Successfully enrolled in ' . $course->title);
        } catch (\Exception $e) {
            return back()->withErrors(['enrollment' => $e->getMessage()]);
        }
    }

    /**
     * Search courses
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100'
        ]);

        $courses = $this->courseService->searchCourses($request->get('query'));

        return response()->json([
            'courses' => $courses
        ]);
    }

    /**
     * Get courses by subject
     */
    public function bySubject(Request $request, string $subject)
    {
        $courses = $this->courseService->getCoursesBySubject($subject);

        return response()->json([
            'courses' => $courses,
            'subject' => $subject
        ]);
    }

    /**
     * Get user's enrolled courses
     */
    public function enrolled(Request $request): Response
    {
        $activeCourses = $this->courseService->getUserActiveCourses($request->user());
        $completedCourses = $request->user()->completedCourses()
                                   ->with('course.coursePrompts')
                                   ->get();

        return Inertia::render('Student/Courses/Enrolled', [
            'activeCourses' => $activeCourses,
            'completedCourses' => $completedCourses,
            'stats' => [
                'totalEnrolled' => $activeCourses->count() + $completedCourses->count(),
                'totalCompleted' => $completedCourses->count(),
                'totalTimeSpent' => $request->user()->total_course_time,
                'averageProgress' => $request->user()->average_course_progress,
            ]
        ]);
    }
}