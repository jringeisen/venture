<?php

namespace App\Services;

use App\Enums\ComplianceReportStatus;
use App\Enums\ComplianceState;
use App\Models\ActiveTime;
use App\Models\ComplianceReport;
use App\Models\LearningSession;
use App\Models\PromptQuestion;
use App\Models\User;
use App\Models\UserCourse;
use Carbon\Carbon;

class ComplianceReportService
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
    ) {}

    /**
     * Generate a full compliance report for a student
     */
    public function generateReport(User $student, Carbon $start, Carbon $end, ComplianceState $state = ComplianceState::FL, ?string $title = null): ComplianceReport
    {
        $parent = $student->parent;
        $title = $title ?? $state->label().' Compliance Report - '.$start->format('M Y').' to '.$end->format('M Y');

        $summaryStatistics = $this->getSummaryStatistics($student, $start, $end);

        $reportMetadata = [
            'daily_activity_log' => $this->getDailyActivityLog($student, $start, $end),
            'course_progress' => $this->getCourseProgress($student, $start, $end),
            'assessment_results' => $this->getAssessmentResults($student, $start, $end),
            'interaction_log' => $this->getInteractionLog($student, $start, $end),
            'attendance_summary' => $this->attendanceService->getAttendanceSummary($student, $start, $end),
            'attendance_log' => $this->attendanceService->getAttendanceLog($student, $start, $end),
        ];

        return ComplianceReport::create([
            'parent_id' => $parent->id,
            'student_id' => $student->id,
            'state' => $state,
            'title' => $title,
            'period_start' => $start,
            'period_end' => $end,
            'summary_statistics' => $summaryStatistics,
            'report_metadata' => $reportMetadata,
            'status' => ComplianceReportStatus::Generated,
            'generated_at' => now(),
        ]);
    }

    /**
     * Get daily activity log from learning_sessions and active_time
     *
     * @return array<int, array{date: string, total_seconds: int, courses_studied: array<string>, sessions: int}>
     */
    public function getDailyActivityLog(User $student, Carbon $start, Carbon $end): array
    {
        $activeTimeEntries = ActiveTime::where('user_id', $student->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date')
            ->get();

        $sessionsByDate = LearningSession::where('user_id', $student->id)
            ->where('started_at', '>=', $start)
            ->where('started_at', '<=', $end->copy()->endOfDay())
            ->with('course:id,title')
            ->get()
            ->groupBy(fn ($session) => $session->started_at->toDateString());

        $log = [];

        foreach ($activeTimeEntries as $entry) {
            $date = $entry->date instanceof \Carbon\Carbon ? $entry->date->toDateString() : $entry->date;
            $daySessions = $sessionsByDate->get($date, collect());
            $coursesStudied = $daySessions
                ->filter(fn ($s) => $s->course)
                ->pluck('course.title')
                ->unique()
                ->values()
                ->toArray();

            $log[] = [
                'date' => $date,
                'total_seconds' => $entry->total_seconds,
                'courses_studied' => $coursesStudied,
                'sessions' => $daySessions->count(),
            ];
        }

        return $log;
    }

    /**
     * Get course progress for the student in the given period
     *
     * @return array<int, array{title: string, progress: int, started_at: ?string, completed_at: ?string, time_spent_minutes: int, learning_objectives: array}>
     */
    public function getCourseProgress(User $student, Carbon $start, Carbon $end): array
    {
        $userCourses = UserCourse::where('user_id', $student->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('started_at', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('started_at', '<=', $end)
                            ->where(function ($q2) use ($start) {
                                $q2->whereNull('completed_at')
                                    ->orWhere('completed_at', '>=', $start);
                            });
                    });
            })
            ->with(['course.coursePrompts.days'])
            ->get();

        return $userCourses->map(function (UserCourse $uc) {
            $objectives = [];

            if ($uc->course && $uc->course->coursePrompts) {
                foreach ($uc->course->coursePrompts as $prompt) {
                    if ($prompt->learning_objectives) {
                        $objectives = array_merge($objectives, $prompt->learning_objectives);
                    }
                }
            }

            return [
                'title' => $uc->course?->title ?? 'Unknown Course',
                'progress' => $uc->progress,
                'started_at' => $uc->started_at?->toDateString(),
                'completed_at' => $uc->completed_at?->toDateString(),
                'time_spent_minutes' => $uc->time_spent_minutes ?? 0,
                'learning_objectives' => $objectives,
            ];
        })->toArray();
    }

    /**
     * Get assessment results (trivia scores) for the period
     *
     * @return array<int, array{course_title: string, scores: array, average: float}>
     */
    public function getAssessmentResults(User $student, Carbon $start, Carbon $end): array
    {
        $userCourses = UserCourse::where('user_id', $student->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('started_at', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('started_at', '<=', $end)
                            ->where(function ($q2) use ($start) {
                                $q2->whereNull('completed_at')
                                    ->orWhere('completed_at', '>=', $start);
                            });
                    });
            })
            ->with('course:id,title')
            ->get();

        return $userCourses
            ->filter(fn (UserCourse $uc) => ! empty($uc->trivia_scores))
            ->map(function (UserCourse $uc) {
                $scores = $uc->trivia_scores ?? [];
                $scoreValues = collect($scores)
                    ->filter(fn ($v) => is_array($v) && isset($v['score']))
                    ->pluck('score');
                $average = $scoreValues->isEmpty() ? 0 : round($scoreValues->avg(), 1);

                return [
                    'course_title' => $uc->course?->title ?? 'Unknown Course',
                    'scores' => $scores,
                    'average' => $average,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get student interaction log (questions + answers)
     *
     * @return array<int, array{date: string, question: string, word_count: int}>
     */
    public function getInteractionLog(User $student, Carbon $start, Carbon $end): array
    {
        return PromptQuestion::where('user_id', $student->id)
            ->whereBetween('created_at', [$start, $end->copy()->endOfDay()])
            ->with('promptAnswer:id,prompt_question_id,word_count')
            ->orderBy('created_at')
            ->get()
            ->map(function (PromptQuestion $pq) {
                return [
                    'date' => $pq->created_at->toDateString(),
                    'question' => $pq->question,
                    'word_count' => $pq->promptAnswer?->word_count ?? 0,
                ];
            })
            ->toArray();
    }

    /**
     * Get aggregated summary statistics
     *
     * @return array{total_instruction_days: int, total_instruction_hours: float, courses_active: int, courses_completed: int, average_trivia_score: float, total_interactions: int}
     */
    public function getSummaryStatistics(User $student, Carbon $start, Carbon $end): array
    {
        $totalDays = ActiveTime::where('user_id', $student->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->count();

        $totalSeconds = ActiveTime::where('user_id', $student->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->sum('total_seconds');

        $activeCourses = UserCourse::where('user_id', $student->id)
            ->whereNull('completed_at')
            ->where('started_at', '<=', $end)
            ->count();

        $completedCourses = UserCourse::where('user_id', $student->id)
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$start, $end])
            ->count();

        $allScores = UserCourse::where('user_id', $student->id)
            ->whereNotNull('trivia_scores')
            ->get()
            ->flatMap(function (UserCourse $uc) {
                return collect($uc->trivia_scores ?? [])
                    ->filter(fn ($v) => is_array($v) && isset($v['score']))
                    ->pluck('score');
            });
        $averageScore = $allScores->isEmpty() ? 0 : round($allScores->avg(), 1);

        $totalInteractions = PromptQuestion::where('user_id', $student->id)
            ->whereBetween('created_at', [$start, $end->copy()->endOfDay()])
            ->count();

        $attendanceSummary = $this->attendanceService->getAttendanceSummary($student, $start, $end);

        return [
            'total_instruction_days' => $totalDays,
            'total_instruction_hours' => round($totalSeconds / 3600, 1),
            'courses_active' => $activeCourses,
            'courses_completed' => $completedCourses,
            'average_trivia_score' => $averageScore,
            'total_interactions' => $totalInteractions,
            'total_attendance_days' => $attendanceSummary['total_attendance_days'],
        ];
    }
}
