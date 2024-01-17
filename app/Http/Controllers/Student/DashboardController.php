<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Services\StudentService;
use App\Services\WordCountService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use NumberFormatter;

class DashboardController extends Controller
{
    public function __construct(
        public WordCountService $wordCountService
    ) {
    }

    public function index(Request $request, StudentService $studentService): Response
    {
        return Inertia::render('Student/Dashboard', [
            'totalQuestions' => $studentService->student($request->user())->totalQuestionsAsked(),
            'dailyQuestions' => $studentService->student($request->user())->totalQuestionsAskedToday(),
            'totalWordsRead' => $this->wordCountService->calculateWordsForPromptAnswers($request->user()),
            'pieChartData' => $studentService->student($request->user())->pieChartData(),
            'randomQuestion' => $this->queryRandomQuestion(),
        ]);
    }

    protected function queryRandomQuestion(): array
    {
        $question = Question::inRandomOrder()->first();
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::ORDINAL);

        if (! $question) {
            return [];
        }

        return [
            'text' => $question->text,
            'category' => $question->category,
            'sub_category' => $question->sub_category,
            'grade' => $numberFormatter->format($question->grade),
            'image' => $question->image,
        ];
    }
}
