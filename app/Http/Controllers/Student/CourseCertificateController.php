<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\CourseService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CourseCertificateController extends Controller
{
    public function __construct(
        private CourseService $courseService,
        private SubscriptionService $subscriptionService,
    ) {}

    /**
     * Display completion certificate for a course
     */
    public function certificate(Request $request, Course $course): InertiaResponse|RedirectResponse
    {
        $user = $request->user();

        if (! $this->subscriptionService->canAccessCertificates($user)) {
            return redirect()
                ->route('student.courses.show', $course)
                ->withErrors(['error' => 'Certificates require the Family plan. Please upgrade to access this feature.']);
        }

        $userProgress = $this->courseService->getUserCourseProgress($user, $course);

        if (! $userProgress || ! $userProgress->completed_at) {
            return redirect()
                ->route('student.courses.show', $course)
                ->withErrors(['error' => 'You must complete the course to view your certificate.']);
        }

        $triviaScores = $userProgress->trivia_scores ?? [];
        $averageScore = 0;
        if (count($triviaScores) > 0) {
            $scores = [];
            foreach ($triviaScores as $weekKey => $data) {
                if (is_array($data) && isset($data['score'])) {
                    $scores[] = $data['score'];
                } elseif (is_numeric($data)) {
                    $scores[] = $data;
                }
            }
            if (count($scores) > 0) {
                $averageScore = round(array_sum($scores) / count($scores));
            }
        }

        $weekTimes = $userProgress->week_times ?? [];
        $totalSeconds = 0;
        foreach ($weekTimes as $weekKey => $seconds) {
            $totalSeconds += (int) $seconds;
        }

        if ($totalSeconds === 0) {
            $totalSeconds = ($userProgress->time_spent_minutes ?? 0) * 60;
        }

        $totalMinutes = (int) floor($totalSeconds / 60);
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0) {
            $timeSpent = "{$hours}h {$minutes}m";
        } elseif ($minutes > 0) {
            $timeSpent = "{$minutes}m";
        } else {
            $timeSpent = "{$totalSeconds}s";
        }

        return Inertia::render('Student/Courses/Certificate', [
            'course' => $course,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'completedAt' => $userProgress->completed_at->format('F j, Y'),
            'startedAt' => $userProgress->started_at?->format('F j, Y') ?? $userProgress->created_at->format('F j, Y'),
            'averageScore' => $averageScore,
            'timeSpent' => $timeSpent,
            'certificateId' => strtoupper(substr(md5($user->id.'-'.$course->id.'-'.$userProgress->completed_at->timestamp), 0, 12)),
        ]);
    }
}
