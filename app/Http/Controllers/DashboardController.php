<?php

namespace App\Http\Controllers;

use App\Models\PromptAnswer;
use App\Models\PromptQuestion;
use App\Services\PieChartService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(PieChartService $pieChartService)
    {
        $result = PromptQuestion::select(
            DB::raw('COUNT(*) as totalQuestions'),
            DB::raw('SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as dailyQuestions')
        )->setBindings([now()->toDateString()])->first();

        return Inertia::render('Dashboard', [
            'totalQuestions' => (int) $result->totalQuestions,
            'dailyQuestions' => (int) $result->dailyQuestions,
            'pieChartData' => $pieChartService
                ->data(PromptAnswer::class, 'subject_category')
                ->labels('subject_category')
                ->series('total')
                ->get(),
        ]);
    }
}
