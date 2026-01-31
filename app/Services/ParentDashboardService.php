<?php

namespace App\Services;

use App\Models\ActiveTime;
use App\Models\DailyQuestionCount;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ParentDashboardService
{
    public function __construct(private readonly SubscriptionService $subscriptionService) {}

    /**
     * @return array{learning_hours_this_week: string, questions_today: int, active_courses: int, plan_label: string, student_count: int, max_students: int|string}
     */
    public function getFamilyStats(User $parent): array
    {
        $studentIds = $parent->students()->pluck('id');
        $timezone = $parent->timezone;

        $weeklySeconds = ActiveTime::query()
            ->whereIn('user_id', $studentIds)
            ->whereBetween('date', [
                now($timezone)->startOfWeek()->toDateString(),
                now($timezone)->endOfWeek()->toDateString(),
            ])
            ->sum('total_seconds');

        $questionsToday = (int) DailyQuestionCount::where('parent_id', $parent->id)
            ->where('date', now($timezone)->toDateString())
            ->value('count') ?? 0;

        $activeCourses = UserCourse::query()
            ->whereIn('user_id', $studentIds)
            ->active()
            ->count();

        $plan = $this->subscriptionService->getCurrentPlan($parent);
        $maxStudents = $plan->isUnlimited('max_students') ? 'Unlimited' : $plan->limit('max_students');

        return [
            'learning_hours_this_week' => $this->formatSeconds($weeklySeconds),
            'questions_today' => $questionsToday,
            'active_courses' => $activeCourses,
            'plan_label' => $plan->label(),
            'student_count' => $parent->students()->count(),
            'max_students' => $maxStudents,
        ];
    }

    /**
     * @return array<int, array{id: int, name: string, grade: ?int, age: ?int, todays_active_time: string, current_streak: int, active_courses_count: int, top_course: ?array{title: string, progress: int}, last_active_at: ?string}>
     */
    public function getStudentCards(User $parent): array
    {
        $timezone = $parent->timezone;
        $today = now($timezone)->toDateString();

        $students = $parent->students()
            ->with([
                'activeTime' => fn ($query) => $query->where('date', $today),
                'userCourses' => fn ($query) => $query->active()->with('course'),
            ])
            ->withCount(['userCourses as active_courses_count' => fn ($query) => $query->active()])
            ->get();

        return $students->map(function (User $student) {
            $todaysSeconds = $student->activeTime->sum('total_seconds');

            $topCourse = $student->userCourses
                ->sortByDesc(fn (UserCourse $uc) => $uc->progress)
                ->first();

            $lastActive = $student->activeTime->sortByDesc('date')->first();

            return [
                'id' => $student->id,
                'name' => $student->name,
                'grade' => $student->grade,
                'age' => $student->age,
                'todays_active_time' => $this->formatSeconds($todaysSeconds),
                'current_streak' => $student->current_streak,
                'active_courses_count' => $student->active_courses_count,
                'top_course' => $topCourse ? [
                    'title' => $topCourse->course->title,
                    'progress' => $topCourse->progress,
                ] : null,
                'last_active_at' => $lastActive ? Carbon::parse($lastActive->date)->diffForHumans() : null,
            ];
        })->values()->toArray();
    }

    /**
     * @return array<int, array{type: string, description: string, timestamp: string, student_name: string}>
     */
    public function getRecentActivity(User $parent, int $limit = 10): array
    {
        $studentIds = $parent->students()->pluck('id');
        $students = $parent->students()->pluck('name', 'id');

        $activities = new Collection;

        // Enrollments
        $enrollments = UserCourse::query()
            ->whereIn('user_id', $studentIds)
            ->whereNotNull('started_at')
            ->with(['course', 'user'])
            ->latest('started_at')
            ->limit($limit)
            ->get()
            ->map(fn (UserCourse $uc) => [
                'type' => 'enrollment',
                'description' => ($students[$uc->user_id] ?? 'Student').' enrolled in '.$uc->course->title,
                'timestamp' => $uc->started_at->diffForHumans(),
                'sort_at' => $uc->started_at,
                'student_name' => $students[$uc->user_id] ?? 'Student',
            ]);

        $activities = $activities->merge($enrollments);

        // Completions
        $completions = UserCourse::query()
            ->whereIn('user_id', $studentIds)
            ->whereNotNull('completed_at')
            ->with(['course', 'user'])
            ->latest('completed_at')
            ->limit($limit)
            ->get()
            ->map(fn (UserCourse $uc) => [
                'type' => 'completion',
                'description' => ($students[$uc->user_id] ?? 'Student').' completed '.$uc->course->title,
                'timestamp' => $uc->completed_at->diffForHumans(),
                'sort_at' => $uc->completed_at,
                'student_name' => $students[$uc->user_id] ?? 'Student',
            ]);

        $activities = $activities->merge($completions);

        // Questions
        $questions = DailyQuestionCount::query()
            ->where('parent_id', $parent->id)
            ->where('count', '>', 0)
            ->with('user')
            ->latest('date')
            ->limit($limit)
            ->get()
            ->map(fn (DailyQuestionCount $dqc) => [
                'type' => 'questions',
                'description' => ($students[$dqc->user_id] ?? 'Student').' asked '.$dqc->count.' question'.($dqc->count !== 1 ? 's' : ''),
                'timestamp' => $dqc->date->diffForHumans(),
                'sort_at' => $dqc->date,
                'student_name' => $students[$dqc->user_id] ?? 'Student',
            ]);

        $activities = $activities->merge($questions);

        return $activities
            ->sortByDesc('sort_at')
            ->take($limit)
            ->map(fn ($item) => collect($item)->except('sort_at')->toArray())
            ->values()
            ->toArray();
    }

    private function formatSeconds(int $seconds): string
    {
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        return $hours.'h '.$minutes.'m';
    }
}
