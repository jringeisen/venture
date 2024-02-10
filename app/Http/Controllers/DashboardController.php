<?php

namespace App\Http\Controllers;

use App\Models\PromptQuestion;
use App\Services\ActiveTimeService;
use App\Services\Charts\Types\LineChartService;
use App\Services\WordCountService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private readonly WordCountService $wordCountService,
        private readonly ActiveTimeService $activeTimeService,
        private readonly LineChartService $lineChartService,
    ) {
    }

    public function __invoke(Request $request)
    {
        $timeframe = $request->get('timeframe', 'yearly');

        return Inertia::render('Dashboard', [
            'timeframe' => $timeframe,
            'totalQuestions' => (int) PromptQuestion::query()
                ->whereHas('user', fn ($query) => $query->where('parent_id', $request->user()->id))
                ->whereHas('promptAnswer')
                ->filterByTimeframe($timeframe)
                ->count(),
            'dailyQuestions' => (int) PromptQuestion::query()
                ->whereHas('user', fn ($query) => $query->where('parent_id', $request->user()->id))
                ->whereHas('promptAnswer')
                ->filterByDate(now())
                ->count(),
            'totalWordsRead' => $this->wordCountService->calculateTotalWordsRead($timeframe),
            'lineChartData' => $this->lineChartService->getDataForUser($timeframe),
            'activeTime' => $this->activeTimeService->fetchTotalTimeForUser($timeframe),
        ]);
    }
}
