<?php

namespace App\Services;

use App\Models\PromptAnswer;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentService
{
    private Student $student;

    private PieChartService $pieChartService;

    public function __construct()
    {
        $this->pieChartService = new PieChartService;
    }

    public function student(Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function totalQuestionsAsked(): int
    {
        return $this->student->promptQuestions()->count();
    }

    public function totalQuestionsAskedToday(): int
    {
        return $this->student
            ->promptQuestions()
            ->filterByDate(now()->toDateString())
            ->count();
    }

    public function categoriesWithCounts(): array
    {
        return $this->student->promptAnswers()
            ->whereNotNull('subject_category')
            ->select('student_id', 'subject_category', DB::raw('count(*) as total'))
            ->groupBy('student_id', 'subject_category')
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('total', 'subject_category')
            ->toArray();
    }

    public function pieChartData()
    {
        return $this->pieChartService
            ->data(PromptAnswer::class, 'subject_category', $this->student->id)
            ->labels('subject_category')
            ->series('total')
            ->get();
    }
}
