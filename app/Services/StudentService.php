<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class StudentService
{
    private User $student;

    public function student(User $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function totalQuestionsAsked(): int
    {
        return $this->student
            ->promptQuestions()
            ->whereHas('promptAnswer')
            ->count();
    }

    public function totalQuestionsAskedToday(): int
    {
        return $this->student
            ->promptQuestions()
            ->whereHas('promptAnswer')
            ->filterByDate(now()->toDateString())
            ->count();
    }

    public function categoriesWithCounts(): array
    {
        return $this->student->promptAnswers()
            ->whereNotNull('subject_category')
            ->select('user_id', 'subject_category', DB::raw('count(*) as total'))
            ->groupBy('user_id', 'subject_category')
            ->orderBy('total', 'desc')
            ->get()
            ->pluck('total', 'subject_category')
            ->toArray();
    }

    public function lineChartData(): array
    {
        return (new LineChartService)
            ->data($this->student->id)
            ->labels('labels')
            ->series('series')
            ->get();
    }

    public function activeTime(): string
    {
        return $this->formatActiveTime(
            $this->student->activeTime->sum('total_seconds')
        );
    }

    protected function formatActiveTime(int $seconds): string
    {
        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        return $days.'d '.$hours.'h '.$minutes.'m';
    }
}
