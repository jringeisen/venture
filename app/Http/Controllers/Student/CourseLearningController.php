<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\EndSessionRequest;
use App\Http\Requests\Student\TrackTimeRequest;
use App\Models\Course;
use App\Models\CoursePrompt;
use App\Models\LearningSession;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseLearningController extends Controller
{
    public function __construct(
        private CourseService $courseService,
    ) {}

    /**
     * Display course learning interface for a specific week and day
     */
    public function learn(Request $request, Course $course, ?int $week = null, ?int $day = null): InertiaResponse|RedirectResponse
    {
        // Ensure user is enrolled
        if (! $request->user()->isEnrolledInCourse($course)) {
            return redirect()
                ->route('student.courses.show', $course)
                ->withErrors(['access' => 'You must be enrolled to access course content.']);
        }

        $userProgress = $this->courseService->getUserCourseProgress($request->user(), $course);

        // Default to current week/day if not specified
        $week = $week ?? $userProgress->current_week;
        $day = $day ?? ($week === $userProgress->current_week ? $userProgress->current_day : 1);

        // Check if user can access this week/day (can't skip ahead)
        if (! $userProgress->canAccessDay($week, $day)) {
            return redirect()
                ->route('student.courses.learn', [
                    'course' => $course->id,
                    'week' => $userProgress->current_week,
                    'day' => $userProgress->current_day,
                ])
                ->withErrors(['access' => 'Complete previous lessons first.']);
        }

        $coursePrompt = CoursePrompt::where('course_id', $course->id)
            ->where('week_number', $week)
            ->with('days')
            ->first();

        if (! $coursePrompt) {
            return redirect()
                ->route('student.courses.show', $course)
                ->withErrors(['week' => 'Week not found.']);
        }

        // Get the specific day
        $courseDay = $coursePrompt->days()->where('day_number', $day)->first();

        // Load course with prompts and their days
        $course->load(['coursePrompts' => function ($query) {
            $query->orderBy('week_number')->with('days');
        }]);

        // Prepare current week data
        $currentWeekData = $coursePrompt->toArray();
        $currentWeekData['trivia_questions'] = $coursePrompt->getTriviaQuestionsForFrontend();

        // Prepare current day data if day exists
        $currentDayData = null;
        if ($courseDay) {
            $currentDayData = $courseDay->toArray();
            $currentDayData['trivia_questions'] = $courseDay->getTriviaQuestionsForFrontend();
        }

        // Calculate total days for the course
        $totalDays = $course->coursePrompts->sum(function ($prompt) {
            return $prompt->days_count ?? $prompt->days->count() ?: 1;
        });

        // Determine if this is the last day of the last week
        $isLastWeek = $week === $course->total_weeks;
        $isLastDayOfWeek = $courseDay
            ? $day >= ($coursePrompt->days_count ?? $coursePrompt->days->count() ?: 1)
            : true;
        $isLastDay = $isLastWeek && $isLastDayOfWeek;

        return Inertia::render('Student/Courses/Learn', [
            'course' => $course,
            'currentWeek' => $currentWeekData,
            'currentDay' => $currentDayData,
            'userProgress' => $userProgress,
            'weekNumber' => $week,
            'dayNumber' => $day,
            'canAdvance' => $week === $userProgress->current_week && $day === $userProgress->current_day,
            'isLastWeek' => $isLastWeek,
            'isLastDay' => $isLastDay,
            'isLastDayOfWeek' => $isLastDayOfWeek,
            'totalDays' => $totalDays,
        ]);
    }

    /**
     * Get course content for streaming
     */
    public function getContent(Request $request, Course $course, int $week): StreamedResponse
    {
        // Ensure user is enrolled
        if (! $request->user()->isEnrolledInCourse($course)) {
            abort(401, 'Unauthorized');
        }

        $coursePrompt = CoursePrompt::where('course_id', $course->id)
            ->where('week_number', $week)
            ->first();

        if (! $coursePrompt) {
            abort(404, 'Week not found');
        }

        $userAge = $request->user()->age ?? 12;

        return response()->stream(function () use ($coursePrompt, $course, $userAge) {
            $content = $this->generateEducationalContent($coursePrompt, $course, $userAge);

            $chunks = str_split($content, 30);

            foreach ($chunks as $chunk) {
                echo 'data: '.json_encode([
                    'delta' => ['content' => $chunk],
                    'finish_reason' => null,
                ])."\n\n";

                usleep(50000);

                if (ob_get_level()) {
                    ob_flush();
                }
                flush();
            }

            echo 'data: '.json_encode([
                'delta' => ['content' => ''],
                'finish_reason' => 'stop',
            ])."\n\n";

        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Start a learning session for tracking time
     */
    public function startSession(Request $request, Course $course, int $week, int $day = 1): JsonResponse
    {
        $user = $request->user();

        if (! $user->isEnrolledInCourse($course)) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        $session = LearningSession::startSession($user->id, $course->id, $week, $day);

        $userCourse = $this->courseService->getUserCourseProgress($user, $course);
        $userCourse?->updateLastAccessed();

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
        ]);
    }

    /**
     * Track time spent on a course/week/day
     */
    public function trackTime(TrackTimeRequest $request, Course $course, int $week, int $day = 1): JsonResponse
    {
        $user = $request->user();

        if (! $user->isEnrolledInCourse($course)) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        $seconds = $request->input('seconds');
        $sessionId = $request->input('session_id');

        if ($sessionId) {
            $session = LearningSession::where('id', $sessionId)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            if ($session) {
                $session->increment('duration_seconds', $seconds);
            }
        }

        $userCourse = $this->courseService->getUserCourseProgress($user, $course);
        if ($userCourse) {
            $userCourse->addDayTime($week, $day, $seconds);
            $userCourse->updateLastAccessed();
        }

        return response()->json(['success' => true]);
    }

    /**
     * End a learning session
     */
    public function endSession(EndSessionRequest $request, Course $course): JsonResponse
    {
        $user = $request->user();
        $sessionId = $request->input('session_id');
        $finalSeconds = $request->input('final_seconds', 0);

        $session = LearningSession::where('id', $sessionId)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if ($session) {
            if ($finalSeconds > 0) {
                $session->increment('duration_seconds', $finalSeconds);

                $userCourse = $this->courseService->getUserCourseProgress($user, $course);
                $userCourse?->addWeekTime($session->week_number, $finalSeconds);
            }

            $session->endSession();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Update progress tracking (called periodically during study)
     */
    public function updateProgress(Request $request, Course $course): JsonResponse
    {
        return response()->json(['success' => true]);
    }

    private function generateEducationalContent(CoursePrompt $prompt, Course $course, int $age): string
    {
        $title = $prompt->title ?? "Week {$prompt->week_number}";
        $description = $prompt->description ?? '';
        $promptText = $prompt->prompt_text ?? '';
        $objectives = $prompt->learning_objectives ?? [];

        $content = "# {$title}\n\n";
        $content .= "Welcome to Week {$prompt->week_number} of {$course->title}!\n\n";

        if ($description) {
            $content .= "## Overview\n\n";
            $content .= "{$description}\n\n";
        }

        if (count($objectives) > 0) {
            $content .= "## What You'll Learn\n\n";
            $content .= "By the end of this lesson, you will be able to:\n\n";
            foreach ($objectives as $objective) {
                $content .= "✓ {$objective}\n";
            }
            $content .= "\n";
        }

        if ($promptText) {
            $content .= "## Let's Explore!\n\n";
            $content .= $this->expandPromptContent($promptText, $age);
            $content .= "\n\n";
        }

        $content .= "## Summary\n\n";
        $content .= "Great job completing this week's lesson! ";
        $content .= "Take some time to review what you've learned, ";
        $content .= "and when you're ready, test your knowledge with the quiz below.\n\n";

        $content .= "---\n\n";
        $content .= '💡 **Tip:** If you want to learn more, try discussing these topics with friends or family!';

        return $content;
    }

    private function expandPromptContent(string $promptText, int $age): string
    {
        $ageGroup = $age <= 8 ? 'young' : ($age <= 12 ? 'middle' : 'teen');

        $intro = match ($ageGroup) {
            'young' => "Let's discover something amazing together! ",
            'middle' => 'Get ready to explore an exciting topic! ',
            'teen' => "Let's dive into this fascinating subject. ",
        };

        return $intro."\n\n".
               "Based on today's topic: \"{$promptText}\"\n\n".
               'This lesson covers important concepts that will help you understand the world around you. '.
               "As you read through this material, think about how these ideas connect to things you already know.\n\n".
               'Remember, learning is a journey - take your time and enjoy exploring!';
    }
}
