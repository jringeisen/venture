<?php

namespace App\Http\Controllers;

use App\Models\PromptAnswer;
use App\Services\PieChartService;
use App\Services\WordCountService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        public WordCountService $wordCountService
    ) {
    }

    public function __invoke(Request $request, PieChartService $pieChartService)
    {
        return Inertia::render('Dashboard', [
            'totalQuestions' => (int) $request->user()->promptQuestions()->whereHas('promptAnswer')->count(),
            'dailyQuestions' => (int) $request->user()->promptQuestions()->whereHas('promptAnswer')->filterByDate(now())->count(),
            'totalWordsRead' => $this->wordCountService->calculateWordsForPromptAnswers($request->user()),
            'pieChartData' => $pieChartService
                ->data(PromptAnswer::class, 'subject_category')
                ->labels('subject_category')
                ->series('total')
                ->get(),
        ]);
    }
}
