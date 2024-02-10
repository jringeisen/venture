<?php

namespace App\Services;

use App\Models\User;
use App\Services\Charts\Types\LineChartService;
use Illuminate\Support\Facades\DB;

class StudentService
{
    private User $student;

    public function __construct(
        private readonly ActiveTimeService $activeTimeService,
        private readonly LineChartService $lineChartService,
    ) {
    }

    public function student(User $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function totalQuestionsAsked(string $timeframe): int
    {
        return $this->student
            ->promptQuestions()
            ->filterByTimeframe($timeframe)
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

    public function lineChartData(string $timeframe = 'yearly'): array
    {
        return $this->lineChartService->getDataForUser($timeframe, $this->student->id);
    }

    public function activeTime(string $timeframe = 'yearly'): string
    {
        return $this->activeTimeService->fetchTotalTimeForUser($timeframe, $this->student->id);
    }
}
