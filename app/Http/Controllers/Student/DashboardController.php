<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request, StudentService $studentService)
    {
        return Inertia::render('Student/Dashboard', [
            'totalQuestions' => $studentService->student($request->user())->totalQuestionsAsked(),
            'dailyQuestions' => $studentService->student($request->user())->totalQuestionsAskedToday(),
            'categoriesWithCounts' => $studentService->student($request->user())->categoriesWithCounts(),
            'pieChartData' => $studentService->student($request->user())->pieChartData(),
        ]);
    }
}
