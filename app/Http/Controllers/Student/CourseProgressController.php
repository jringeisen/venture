<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseDay;
use App\Models\CoursePrompt;
use App\Models\LearningSession;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseProgressController extends Controller
{
    public function __construct(
        private CourseService $courseService
    ) {}

    /**
     * Display course learning interface for a specific week and day
     */
    public function learn(Request $request, Course $course, ?int $week = null, ?int $day = null): InertiaResponse
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
     * Complete current week and advance to next (legacy method, kept for backwards compatibility)
     */
    public function completeWeek(Request $request, Course $course, int $week)
    {
        $request->validate([
            'trivia_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $user = $request->user();

        $userProgress = $this->courseService->getUserCourseProgress($user, $course);
        if (! $userProgress) {
            return back()->withErrors(['error' => 'User not enrolled in course']);
        }

        // Only allow completing current week
        if ($week !== $userProgress->current_week) {
            return back()->withErrors(['error' => 'You can only complete your current week']);
        }

        // Record trivia score if provided
        if ($request->has('trivia_score') && $request->trivia_score !== null) {
            $userProgress->recordTriviaScore($week, (int) $request->trivia_score);
        }

        // Use total_weeks accessor which counts actual course prompts
        $totalWeeks = $course->total_weeks;

        // Check if this is the last week
        if ($week >= $totalWeeks) {
            // Complete the course
            $this->courseService->completeCourse($user, $course);

            return redirect()
                ->route('student.courses.show', $course)
                ->with('success', 'Congratulations! You have completed the course!');
        }

        // Advance to next week
        $userProgress->advanceToNextWeek();

        return redirect()
            ->route('student.courses.learn', ['course' => $course->id, 'week' => $week + 1])
            ->with('success', 'Week '.$week.' completed! Starting Week '.($week + 1).'.');
    }

    /**
     * Complete current day and advance to next day or week
     */
    public function completeDay(Request $request, Course $course, int $week, int $day)
    {
        $request->validate([
            'trivia_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $user = $request->user();

        $userProgress = $this->courseService->getUserCourseProgress($user, $course);
        if (! $userProgress) {
            return back()->withErrors(['error' => 'User not enrolled in course']);
        }

        // Only allow completing current day
        if ($week !== $userProgress->current_week || $day !== $userProgress->current_day) {
            return back()->withErrors(['error' => 'You can only complete your current day']);
        }

        // Record trivia score if provided
        if ($request->has('trivia_score') && $request->trivia_score !== null) {
            $userProgress->recordDayTriviaScore($week, $day, (int) $request->trivia_score);
        }

        // Get current week prompt to check days
        $coursePrompt = CoursePrompt::where('course_id', $course->id)
            ->where('week_number', $week)
            ->first();

        $totalDaysInWeek = $coursePrompt->days_count ?? $coursePrompt->days()->count() ?: 1;
        $totalWeeks = $course->total_weeks;

        // Check if this is the last day of the week
        if ($day >= $totalDaysInWeek) {
            // Check if this is the last week
            if ($week >= $totalWeeks) {
                // Complete the course
                $this->courseService->completeCourse($user, $course);

                return redirect()
                    ->route('student.courses.show', $course)
                    ->with('success', 'Congratulations! You have completed the course!');
            }

            // Advance to next week (resets day to 1)
            $userProgress->advanceToNextWeek();

            return redirect()
                ->route('student.courses.learn', ['course' => $course->id, 'week' => $week + 1, 'day' => 1])
                ->with('success', 'Week ' . $week . ' completed! Starting Week ' . ($week + 1) . '.');
        }

        // Advance to next day within the same week
        $userProgress->advanceToNextDay();

        return redirect()
            ->route('student.courses.learn', ['course' => $course->id, 'week' => $week, 'day' => $day + 1])
            ->with('success', 'Day ' . $day . ' completed! Starting Day ' . ($day + 1) . '.');
    }

    /**
     * Get course content for streaming (integrate with existing AI system)
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
            // Build educational content based on the prompt
            $content = $this->generateEducationalContent($coursePrompt, $course, $userAge);

            // Stream content in chunks for a nice UX
            $chunks = str_split($content, 30);

            foreach ($chunks as $chunk) {
                echo 'data: '.json_encode([
                    'delta' => ['content' => $chunk],
                    'finish_reason' => null,
                ])."\n\n";

                usleep(50000); // 50ms delay for smooth streaming effect

                if (ob_get_level()) {
                    ob_flush();
                }
                flush();
            }

            // Send completion signal
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
     * Generate educational content based on course prompt
     */
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

        // Add main content based on the prompt text
        if ($promptText) {
            $content .= "## Let's Explore!\n\n";
            $content .= $this->expandPromptContent($promptText, $age);
            $content .= "\n\n";
        }

        // Add a summary section
        $content .= "## Summary\n\n";
        $content .= "Great job completing this week's lesson! ";
        $content .= "Take some time to review what you've learned, ";
        $content .= "and when you're ready, test your knowledge with the quiz below.\n\n";

        $content .= "---\n\n";
        $content .= '💡 **Tip:** If you want to learn more, try discussing these topics with friends or family!';

        return $content;
    }

    /**
     * Expand prompt text into educational content
     */
    private function expandPromptContent(string $promptText, int $age): string
    {
        // This creates placeholder educational content
        // In a real implementation, this would call an AI service
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

    /**
     * Update progress tracking (called periodically during study)
     */
    public function updateProgress(Request $request, Course $course)
    {
        // Simplified - just return success since time tracking columns don't exist
        return response()->json(['success' => true]);
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

        // Update last accessed
        $userCourse = $this->courseService->getUserCourseProgress($user, $course);
        $userCourse?->updateLastAccessed();

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
        ]);
    }

    /**
     * Track time spent on a course/week/day (called periodically)
     */
    public function trackTime(Request $request, Course $course, int $week, int $day = 1): JsonResponse
    {
        $request->validate([
            'seconds' => 'required|integer|min:1|max:3600',
            'session_id' => 'nullable|integer',
        ]);

        $user = $request->user();

        if (! $user->isEnrolledInCourse($course)) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        $seconds = $request->input('seconds');
        $sessionId = $request->input('session_id');

        // Update learning session if provided
        if ($sessionId) {
            $session = LearningSession::where('id', $sessionId)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            if ($session) {
                $session->increment('duration_seconds', $seconds);
            }
        }

        // Update user course time at day level
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
    public function endSession(Request $request, Course $course): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|integer',
            'final_seconds' => 'nullable|integer|min:0',
        ]);

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

                // Also update user course time for the week
                $userCourse = $this->courseService->getUserCourseProgress($user, $course);
                $userCourse?->addWeekTime($session->week_number, $finalSeconds);
            }

            $session->endSession();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get trivia questions for a week
     */
    public function getTrivia(Request $request, Course $course, int $week)
    {
        // Ensure user is enrolled
        if (! $request->user()->isEnrolledInCourse($course)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $coursePrompt = CoursePrompt::where('course_id', $course->id)
            ->where('week_number', $week)
            ->first();

        if (! $coursePrompt || ! $coursePrompt->trivia_questions) {
            return response()->json(['error' => 'No trivia available for this week'], 404);
        }

        return response()->json([
            'questions' => $coursePrompt->trivia_questions,
            'week' => $week,
            'course' => $course->title,
        ]);
    }

    /**
     * Submit trivia answers and get score
     */
    public function submitTrivia(Request $request, Course $course, int $week)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $coursePrompt = CoursePrompt::where('course_id', $course->id)
            ->where('week_number', $week)
            ->first();

        if (! $coursePrompt || ! $coursePrompt->trivia_questions) {
            return response()->json(['error' => 'No trivia available'], 404);
        }

        // Calculate score (simple implementation)
        $questions = $coursePrompt->trivia_questions;
        $answers = $request->get('answers');
        $correct = 0;
        $total = count($questions);

        foreach ($questions as $index => $question) {
            if (isset($answers[$index]) && $answers[$index] === $question['correct_answer']) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100, 1) : 0;

        // Record the score
        $userProgress = $this->courseService->getUserCourseProgress($request->user(), $course);
        if ($userProgress) {
            $userProgress->recordTriviaScore($week, $score);
        }

        return response()->json([
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'passed' => $score >= 70, // 70% passing grade
        ]);
    }

    /**
     * Display completion certificate for a course
     */
    public function certificate(Request $request, Course $course): InertiaResponse
    {
        $user = $request->user();

        // Check if user is enrolled and has completed the course
        $userProgress = $this->courseService->getUserCourseProgress($user, $course);

        if (! $userProgress || ! $userProgress->completed_at) {
            return redirect()
                ->route('student.courses.show', $course)
                ->withErrors(['error' => 'You must complete the course to view your certificate.']);
        }

        // Calculate average trivia score from week-keyed data
        $triviaScores = $userProgress->trivia_scores ?? [];
        $averageScore = 0;
        if (count($triviaScores) > 0) {
            $scores = [];
            foreach ($triviaScores as $weekKey => $data) {
                // Handle both formats: direct score or nested array with 'score' key
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

        // Calculate total time spent from week_times (more accurate than time_spent_minutes)
        $weekTimes = $userProgress->week_times ?? [];
        $totalSeconds = 0;
        foreach ($weekTimes as $weekKey => $seconds) {
            $totalSeconds += (int) $seconds;
        }

        // Fall back to time_spent_minutes if week_times is empty
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
            // Show seconds if less than a minute
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
            'certificateId' => strtoupper(substr(md5($user->id . '-' . $course->id . '-' . $userProgress->completed_at->timestamp), 0, 12)),
        ]);
    }
}
