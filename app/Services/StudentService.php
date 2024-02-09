<?php

namespace App\Services;

use App\Models\User;
use App\Services\Charts\Types\LineChartService;
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

    public function lineChartData(string $timeframe = 'yearly'): array
    {
        return (new LineChartService($timeframe))->getDataForUser($this->student->id);
    }

    public function activeTime(string $timeframe = 'yearly'): string
    {
        if ($timeframe === 'weekly') {
            return $this->formatActiveTime(
                DB::table('active_time')
                    ->where('user_id', $this->student->id)
                    ->whereBetween('created_at', [
                        now(request()->user()->timezone)->startOfWeek(),
                        now(request()->user()->timezone)->endOfWeek(),
                    ])
                    ->sum('total_seconds')
            );
        }

        if ($timeframe === 'monthly') {
            return $this->formatActiveTime(
                DB::table('active_time')
                    ->where('user_id', $this->student->id)
                    ->whereBetween('created_at', [
                        now(request()->user()->timezone)->startOfMonth(),
                        now(request()->user()->timezone)->endOfMonth(),
                    ])
                    ->sum('total_seconds')
            );
        }

        return $this->formatActiveTime(
            DB::table('active_time')
                ->where('user_id', $this->student->id)
                ->whereYear('created_at', now(request()->user()->timezone)->year)
                ->sum('total_seconds')
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
