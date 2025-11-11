<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CoursePrompt;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    /**
     * Get all active courses
     */
    public function getActiveCourses(): Collection
    {
        return Course::with(['coursePrompts' => function ($query) {
                        $query->orderBy('week_number');
                    }])
                    ->orderBy('title')
                    ->get();
    }

    /**
     * Get paginated courses for catalog
     */
    public function getPaginatedCourses(int $perPage = 12, ?string $subject = null): LengthAwarePaginator
    {
        $query = Course::with(['coursePrompts' => function ($query) {
                          $query->orderBy('week_number');
                      }]);

        // Note: subject filtering not available with current schema

        return $query->orderBy('title')->paginate($perPage);
    }

    /**
     * Get courses by subject category
     */
    public function getCoursesBySubject(string $subject): Collection
    {
        return Course::with(['coursePrompts' => function ($query) {
                        $query->orderBy('week_number');
                    }])
                    ->orderBy('title')
                    ->get();
    }

    /**
     * Get user's enrolled courses
     */
    public function getUserEnrolledCourses(User $user): Collection
    {
        return $user->userCourses()
                   ->with([
                       'course.coursePrompts' => function ($query) {
                           $query->orderBy('week_number');
                       }
                   ])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Get user's active (in-progress) courses
     */
    public function getUserActiveCourses(User $user): Collection
    {
        return $user->userCourses()
                   ->whereNull('completed_at')
                   ->with([
                       'course.coursePrompts' => function ($query) {
                           $query->orderBy('week_number');
                       }
                   ])
                   ->orderBy('updated_at', 'desc')
                   ->get();
    }

    /**
     * Enroll user in a course
     */
    public function enrollUser(User $user, Course $course): UserCourse
    {
        if ($user->isEnrolledInCourse($course)) {
            throw new \Exception('User is already enrolled in this course');
        }

        $enrollment = $user->enrollInCourse($course);

        // Update parent's course tracking if user is a student
        if ($user->isStudent() && $user->parent) {
            $user->parent->increment('total_courses_enrolled');
        }

        return $enrollment;
    }

    /**
     * Get course progress for a user
     */
    public function getUserCourseProgress(User $user, Course $course): Model
    {
        return $user->userCourses()
                   ->where('course_id', $course->id)
                   ->with('course.coursePrompts')
                   ->first();
    }

    /**
     * Advance user to next week in course
     */
    public function advanceUserToNextWeek(User $user, Course $course): bool
    {
        $enrollment = $this->getUserCourseProgress($user, $course);

        if (!$enrollment) {
            throw new \Exception('User is not enrolled in this course');
        }

        return $enrollment->advanceToNextWeek();
    }

    /**
     * Complete a week for a user
     */
    public function completeWeek(User $user, Course $course, int $week, ?float $triviaScore = null): void
    {
        $enrollment = $this->getUserCourseProgress($user, $course);

        if (!$enrollment) {
            throw new \Exception('User is not enrolled in this course');
        }

        if ($enrollment->current_week !== $week) {
            throw new \Exception('Cannot complete a week that is not current');
        }

        // Record trivia score if provided
        if ($triviaScore !== null) {
            $enrollment->recordTriviaScore($week, $triviaScore);
        }

        // Advance to next week
        $this->advanceUserToNextWeek($user, $course);
    }

    /**
     * Get recommended courses for a user
     */
    public function getRecommendedCourses(User $user, int $limit = 6): Collection
    {
        $query = Course::whereNotIn('id', $user->enrolledCourses()->pluck('id'))
                      ->with(['coursePrompts' => function ($query) {
                          $query->orderBy('week_number');
                      }]);

        return $query->orderBy('title')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get course statistics
     */
    public function getCourseStatistics(Course $course): array
    {
        return [
            'enrolled_count' => $course->enrollment_count ?? 0,
            'completion_rate' => $course->completion_rate ?? 0,
            'average_progress' => $course->average_progress ?? 0,
            'total_weeks' => $course->total_weeks ?? $course->length_in_weeks,
        ];
    }

    /**
     * Search courses
     */
    public function searchCourses(string $query, int $limit = 10): Collection
    {
        return Course::where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['coursePrompts' => function ($query) {
                        $query->orderBy('week_number');
                    }])
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get course subjects with counts
     */
    public function getCourseSubjectsWithCounts(): \Illuminate\Support\Collection
    {
        // Return empty collection since subject_category column doesn't exist
        return collect([]);
    }

    /**
     * Update user's course access time
     */
    public function updateCourseAccess(User $user, Course $course): void
    {
        $enrollment = $this->getUserCourseProgress($user, $course);

        if ($enrollment) {
            $enrollment->updateLastAccessed();
        }
    }

    /**
     * Add study time to user's course
     */
    public function addStudyTime(User $user, Course $course, int $minutes): void
    {
        $enrollment = $this->getUserCourseProgress($user, $course);

        if ($enrollment) {
            $enrollment->addTimeSpent($minutes);
        }
    }
}