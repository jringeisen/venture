<?php

namespace App\Services;

use App\Models\ActiveTime;
use App\Models\Attendance;
use App\Models\LearningSession;
use App\Models\PromptQuestion;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Get monthly attendance keyed by date.
     *
     * @return array<string, array{id: int, type: string, label: string, notes: ?string}>
     */
    public function getMonthlyAttendance(User $student, int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        return Attendance::where('user_id', $student->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(fn (Attendance $a) => $a->date->toDateString())
            ->map(fn (Attendance $a) => [
                'id' => $a->id,
                'type' => $a->type->value,
                'label' => $a->type->label(),
                'notes' => $a->notes,
            ])
            ->toArray();
    }

    /**
     * Get a daily work overview for a specific date.
     *
     * @return array{active_time_seconds: int, courses: array, questions: array, question_count: int}
     */
    public function getDailyWorkOverview(User $student, string $date): array
    {
        $timezone = $student->timezone;
        $dayStart = Carbon::parse($date, $timezone)->startOfDay()->utc();
        $dayEnd = Carbon::parse($date, $timezone)->endOfDay()->utc();

        $activeTime = ActiveTime::where('user_id', $student->id)
            ->where('date', $date)
            ->first();

        $sessions = LearningSession::where('user_id', $student->id)
            ->where('started_at', '>=', $dayStart)
            ->where('started_at', '<=', $dayEnd)
            ->with('course:id,title')
            ->get();

        $courses = $sessions
            ->filter(fn ($s) => $s->course)
            ->groupBy('course_id')
            ->map(fn ($group) => [
                'title' => $group->first()->course->title,
                'duration_seconds' => $group->sum('duration_seconds'),
            ])
            ->values()
            ->toArray();

        $questions = PromptQuestion::where('user_id', $student->id)
            ->where('created_at', '>=', $dayStart)
            ->where('created_at', '<=', $dayEnd)
            ->get(['id', 'question', 'created_at'])
            ->map(fn (PromptQuestion $pq) => [
                'question' => $pq->question,
                'time' => $pq->created_at->timezone($timezone)->format('g:i A'),
            ])
            ->toArray();

        return [
            'active_time_seconds' => $activeTime?->total_seconds ?? 0,
            'courses' => $courses,
            'questions' => $questions,
            'question_count' => count($questions),
        ];
    }

    /**
     * Get attendance summary counts by type for a date range.
     *
     * @return array{present: int, field_trip: int, offline_day: int, excused_absence: int, total_attendance_days: int}
     */
    public function getAttendanceSummary(User $student, Carbon $start, Carbon $end): array
    {
        $records = Attendance::where('user_id', $student->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get();

        $counts = [
            'present' => 0,
            'field_trip' => 0,
            'offline_day' => 0,
            'excused_absence' => 0,
        ];

        foreach ($records as $record) {
            $counts[$record->type->value]++;
        }

        $counts['total_attendance_days'] = $counts['present'] + $counts['field_trip'] + $counts['offline_day'];

        return $counts;
    }

    /**
     * Get attendance log entries for a date range.
     *
     * @return array<int, array{date: string, type: string, label: string, notes: ?string}>
     */
    public function getAttendanceLog(User $student, Carbon $start, Carbon $end): array
    {
        return Attendance::where('user_id', $student->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get()
            ->map(fn (Attendance $a) => [
                'date' => $a->date->toDateString(),
                'type' => $a->type->value,
                'label' => $a->type->label(),
                'notes' => $a->notes,
            ])
            ->toArray();
    }
}
