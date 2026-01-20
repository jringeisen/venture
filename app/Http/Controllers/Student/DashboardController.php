<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Services\WordCountService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly WordCountService $wordCountService,
    ) {}

    public function index(Request $request): Response
    {
        $timeframe = $request->get('timeframe', 'yearly');
        $studentService = app(StudentService::class);
        $user = $request->user();

        $enrolledCourses = $user->enrolledCourses()
            ->withPivot(['current_week', 'started_at', 'completed_at'])
            ->get()
            ->map(function ($course) {
                $totalWeeks = $course->length_in_weeks ?? $course->coursePrompts()->count();
                $currentWeek = $course->pivot->current_week ?? 1;
                $progress = $totalWeeks > 0 ? round((($currentWeek - 1) / $totalWeeks) * 100) : 0;

                if ($course->pivot->completed_at) {
                    $progress = 100;
                }

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'image_url' => $course->image_url,
                    'length_in_weeks' => $totalWeeks,
                    'current_week' => $currentWeek,
                    'progress' => $progress,
                    'is_completed' => ! is_null($course->pivot->completed_at),
                    'started_at' => $course->pivot->started_at,
                ];
            });

        return Inertia::render('Student/Dashboard', [
            'totalQuestions' => $studentService->student($user)->totalQuestionsAsked($timeframe),
            'dailyQuestions' => $studentService->student($user)->totalQuestionsAskedToday(),
            'totalWordsRead' => $this->wordCountService->calculateTotalWordsRead($timeframe, $user->id),
            'lineChartData' => $studentService->lineChartData($timeframe),
            'activeTime' => $studentService->student($user)->activeTime($timeframe),
            'timeframe' => $timeframe,
            'enrolledCourses' => $enrolledCourses,
        ]);
    }
}
