<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentService
{
    private User $student;

    private PieChartService $pieChartService;

    public function __construct()
    {
        $this->pieChartService = new PieChartService;
    }

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
        $baseMonths = [
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sept' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0,
        ];

        $months = DB::table('active_time')
            ->selectRaw('MONTH(created_at) as month, SUM(total_seconds) as total_seconds')
            ->groupBy('month')
            ->get()
            ->reduce(function ($carry, $item) {
                $monthName = Carbon::createFromFormat('!m', $item->month)->format('M');
                $carry[$monthName] = $item->total_seconds;

                return $carry;
            }, []);

        $values = array_merge($baseMonths, $months);

        $labels = array_keys($values);
        $series = array_values($values);

        return compact('labels', 'series');
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
