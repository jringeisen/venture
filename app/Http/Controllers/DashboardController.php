<?php

namespace App\Http\Controllers;

use App\Models\PromptAnswer;
use App\Models\PromptQuestion;
use App\Services\PieChartService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(PieChartService $pieChartService)
    {
        return Inertia::render('Dashboard', [
            'totalQuestions' => (int) PromptQuestion::whereHas('promptAnswer')->count(),
            'dailyQuestions' => (int) PromptQuestion::filterByDate(now()->toDateString())
                ->whereHas('promptAnswer')
                ->count(),
            'pieChartData' => $pieChartService
                ->data(PromptAnswer::class, 'subject_category')
                ->labels('subject_category')
                ->series('total')
                ->get(),
        ]);
    }
}
