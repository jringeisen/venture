<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursePrompt;
use App\Services\CourseService;
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
     * Display course learning interface for a specific week
     */
    public function learn(Request $request, Course $course, int $week = 1): InertiaResponse
    {
        // Ensure user is enrolled
        if (! $request->user()->isEnrolledInCourse($course)) {
            return redirect()
                ->route('student.courses.show', $course)
                ->withErrors(['access' => 'You must be enrolled to access course content.']);
        }

        $userProgress = $this->courseService->getUserCourseProgress($request->user(), $course);

        // Check if user can access this week (can't skip ahead)
        if ($week > $userProgress->current_week && $week > 1) {
            return redirect()
                ->route('student.courses.learn', ['course' => $course->id, 'week' => $userProgress->current_week])
                ->withErrors(['access' => 'Complete previous weeks first.']);
        }

        $coursePrompt = CoursePrompt::where('course_id', $course->id)
            ->where('week_number', $week)
            ->first();

        if (! $coursePrompt) {
            return redirect()
                ->route('student.courses.show', $course)
                ->withErrors(['week' => 'Week not found.']);
        }

        // Note: Access tracking simplified due to schema limitations

        $course->load(['coursePrompts' => function ($query) {
            $query->orderBy('week_number');
        }]);

        // Prepare current week data with formatted trivia questions
        $currentWeekData = $coursePrompt->toArray();
        $currentWeekData['trivia_questions'] = $coursePrompt->getTriviaQuestionsForFrontend();

        return Inertia::render('Student/Courses/Learn', [
            'course' => $course,
            'currentWeek' => $currentWeekData,
            'userProgress' => $userProgress,
            'weekNumber' => $week,
            'canAdvance' => $week === $userProgress->current_week,
            'isLastWeek' => $week === $course->total_weeks,
        ]);
    }

    /**
     * Complete current week and advance to next
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

        $totalWeeks = $course->length_in_weeks ?? $course->coursePrompts()->count();

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
}
