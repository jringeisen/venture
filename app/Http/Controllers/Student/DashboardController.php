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
        private readonly StudentService $studentService,
    ) {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Student/Dashboard', [
            'totalQuestions' => $this->studentService->student($request->user())->totalQuestionsAsked(),
            'dailyQuestions' => $this->studentService->student($request->user())->totalQuestionsAskedToday(),
            'totalWordsRead' => $this->wordCountService->calculateWordsForPromptAnswers($request->user()),
            'lineChartData' => $this->studentService->lineChartData(),
            'activeTime' => $this->studentService->student($request->user())->activeTime(),
        ]);
    }
}
