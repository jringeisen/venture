<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PromptAnswer;
use App\Services\PieChartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request, PieChartService $pieChartService)
    {
        return Inertia::render('Student/Dashboard', [
            'totalQuestions' => $request->user()->promptQuestions()->count(),
            'dailyQuestions' => $request->user()
                ->promptQuestions()
                ->filterByDate(today()->toDateString())
                ->count(),
            'promptsWithCounts' => $request->user()->promptAnswers()
                ->whereNotNull('subject_category')
                ->select('student_id', 'subject_category', DB::raw('count(*) as total'))
                ->groupBy('student_id', 'subject_category')
                ->get()
                ->pluck('total', 'subject_category')
                ->toArray(),
            'pieChartData' => $pieChartService
                ->data(PromptAnswer::class, 'subject_category', $request->user()->id)
                ->labels('subject_category')
                ->series('total')
                ->get(),
        ]);
    }
}
