<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\CompleteDayRequest;
use App\Http\Requests\Student\SubmitTriviaRequest;
use App\Models\Course;
use App\Models\CoursePrompt;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseProgressController extends Controller
{
    public function __construct(
        private CourseService $courseService,
    ) {}

    /**
     * Complete current week and advance to next (legacy method)
     */
    public function completeWeek(CompleteDayRequest $request, Course $course, int $week)
    {
        $user = $request->user();

        $userProgress = $this->courseService->getUserCourseProgress($user, $course);
        if (! $userProgress) {
            return back()->withErrors(['error' => 'User not enrolled in course']);
        }

        if ($week !== $userProgress->current_week) {
            return back()->withErrors(['error' => 'You can only complete your current week']);
        }

        if ($request->has('trivia_score') && $request->trivia_score !== null) {
            $userProgress->recordTriviaScore($week, (int) $request->trivia_score);
        }

        $totalWeeks = $course->total_weeks;

        if ($week >= $totalWeeks) {
            $this->courseService->completeCourse($user, $course);

            return redirect()
                ->route('student.courses.show', $course)
                ->with('success', 'Congratulations! You have completed the course!');
        }

        $userProgress->advanceToNextWeek();

        return redirect()
            ->route('student.courses.learn', ['course' => $course->id, 'week' => $week + 1])
            ->with('success', 'Week '.$week.' completed! Starting Week '.($week + 1).'.');
    }

    /**
     * Complete current day and advance to next day or week
     */
    public function completeDay(CompleteDayRequest $request, Course $course, int $week, int $day)
    {
        $user = $request->user();

        $userProgress = $this->courseService->getUserCourseProgress($user, $course);
        if (! $userProgress) {
            return back()->withErrors(['error' => 'User not enrolled in course']);
        }

        if ($week !== $userProgress->current_week || $day !== $userProgress->current_day) {
            return back()->withErrors(['error' => 'You can only complete your current day']);
        }

        if ($request->has('trivia_score') && $request->trivia_score !== null) {
            $userProgress->recordDayTriviaScore($week, $day, (int) $request->trivia_score);
        }

        $coursePrompt = CoursePrompt::where('course_id', $course->id)
            ->where('week_number', $week)
            ->first();

        $totalDaysInWeek = $coursePrompt->days_count ?? $coursePrompt->days()->count() ?: 1;
        $totalWeeks = $course->total_weeks;

        if ($day >= $totalDaysInWeek) {
            if ($week >= $totalWeeks) {
                $this->courseService->completeCourse($user, $course);

                return redirect()
                    ->route('student.courses.show', $course)
                    ->with('success', 'Congratulations! You have completed the course!');
            }

            $userProgress->advanceToNextWeek();

            return redirect()
                ->route('student.courses.learn', ['course' => $course->id, 'week' => $week + 1, 'day' => 1])
                ->with('success', 'Week '.$week.' completed! Starting Week '.($week + 1).'.');
        }

        $userProgress->advanceToNextDay();

        return redirect()
            ->route('student.courses.learn', ['course' => $course->id, 'week' => $week, 'day' => $day + 1])
            ->with('success', 'Day '.$day.' completed! Starting Day '.($day + 1).'.');
    }

    /**
     * Get trivia questions for a week
     */
    public function getTrivia(Request $request, Course $course, int $week)
    {
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
    public function submitTrivia(SubmitTriviaRequest $request, Course $course, int $week)
    {
        $coursePrompt = CoursePrompt::where('course_id', $course->id)
            ->where('week_number', $week)
            ->first();

        if (! $coursePrompt || ! $coursePrompt->trivia_questions) {
            return response()->json(['error' => 'No trivia available'], 404);
        }

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

        $userProgress = $this->courseService->getUserCourseProgress($request->user(), $course);
        if ($userProgress) {
            $userProgress->recordTriviaScore($week, $score);
        }

        return response()->json([
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'passed' => $score >= 70,
        ]);
    }
}
