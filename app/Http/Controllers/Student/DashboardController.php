<?php

namespace App\Http\Controllers\Student;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Student/Dashboard', [
            'promptsWithCounts' => $request->user()->promptAnswers()
                ->whereNotNull('subject_category')
                ->select('student_id', 'subject_category', DB::raw('count(*) as total'))
                ->groupBy('student_id', 'subject_category')
                ->get()
                ->pluck('total', 'subject_category')
                ->toArray(),
        ]);
    }
}
