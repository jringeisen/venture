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
    ) {
    }

    public function index(Request $request): Response
    {
        $timeframe = $request->get('timeframe', 'yearly');
        $studentService = app(StudentService::class);

        return Inertia::render('Student/Dashboard', [
            'totalQuestions' => $studentService->student($request->user())->totalQuestionsAsked($timeframe),
            'dailyQuestions' => $studentService->student($request->user())->totalQuestionsAskedToday(),
            'totalWordsRead' => $this->wordCountService->calculateTotalWordsRead($request->user(), $timeframe),
            'lineChartData' => $studentService->lineChartData($timeframe),
            'activeTime' => $studentService->student($request->user())->activeTime($timeframe),
            'timeframe' => $timeframe,
        ]);
    }
}
