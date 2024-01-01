<?php

namespace App\Http\Controllers;

use App\Models\PromptAnswer;
use App\Services\PieChartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request, PieChartService $pieChartService)
    {
        return Inertia::render('Dashboard', [
            'totalQuestions' => (int) $request->user()->promptQuestions()->whereHas('promptAnswer')->count(),
            'dailyQuestions' => (int) $request->user()->promptQuestions()->whereHas('promptAnswer')->filterByDate(now())->count(),
            'pieChartData' => $pieChartService
                ->data(PromptAnswer::class, 'subject_category')
                ->labels('subject_category')
                ->series('total')
                ->get(),
        ]);
    }
}
