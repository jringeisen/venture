<?php

namespace App\Services;

use App\Enums\SubscriptionPlan;
use App\Models\ComplianceReport;
use App\Models\DailyQuestionCount;
use App\Models\User;

class SubscriptionService
{
    /**
     * Resolve the billing parent from any user (parent or student).
     */
    public function getBillingUser(User $user): User
    {
        return $user->isStudent() ? $user->parent : $user;
    }

    /**
     * Determine the current subscription plan for a user.
     */
    public function getCurrentPlan(User $user): SubscriptionPlan
    {
        $billingUser = $this->getBillingUser($user);

        if ($billingUser->grandfathered) {
            return SubscriptionPlan::Family;
        }

        $subscription = $billingUser->subscription('default');

        if (! $subscription || ! $subscription->active()) {
            return SubscriptionPlan::Free;
        }

        return SubscriptionPlan::fromStripePrice($subscription->stripe_price);
    }

    /**
     * Check if the student can ask another AI question this hour.
     */
    public function canAskQuestion(User $user): bool
    {
        $plan = $this->getCurrentPlan($user);

        if ($plan->isUnlimited('ai_questions_per_hour')) {
            return true;
        }

        $student = $user->isStudent() ? $user : $user;
        $hourlyCount = $this->getHourlyQuestionCount($student);

        return $hourlyCount < $plan->limit('ai_questions_per_hour');
    }

    /**
     * Record that a question was asked (per-student, per-hour).
     */
    public function recordQuestion(User $user): void
    {
        $student = $user->isStudent() ? $user : $user;
        $billingUser = $this->getBillingUser($user);

        DailyQuestionCount::updateOrCreate(
            [
                'user_id' => $student->id,
                'date' => now($billingUser->timezone)->toDateString(),
                'hour' => now($billingUser->timezone)->hour,
            ],
            [
                'parent_id' => $billingUser->id,
            ]
        )->increment('count');
    }

    /**
     * Check if a student can enroll in another course.
     */
    public function canEnrollInCourse(User $user): bool
    {
        $plan = $this->getCurrentPlan($user);

        if ($plan->isUnlimited('active_courses_per_student')) {
            return true;
        }

        $activeCourseCount = $user->activeCourses()->count();

        return $activeCourseCount < $plan->limit('active_courses_per_student');
    }

    /**
     * Check if the parent can generate another compliance report this month.
     */
    public function canGenerateReport(User $user): bool
    {
        $plan = $this->getCurrentPlan($user);
        $limit = $plan->limit('compliance_reports_per_month');

        if ($limit === -1) {
            return true;
        }

        if ($limit === 0) {
            return false;
        }

        $billingUser = $this->getBillingUser($user);
        $monthlyCount = ComplianceReport::where('parent_id', $billingUser->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        return $monthlyCount < $limit;
    }

    /**
     * Check if the parent can add another student.
     */
    public function canAddStudent(User $user): bool
    {
        $plan = $this->getCurrentPlan($user);
        $billingUser = $this->getBillingUser($user);
        $studentCount = $billingUser->students()->count();

        return $studentCount < $plan->limit('max_students');
    }

    /**
     * Check if the user can download compliance PDFs.
     */
    public function canDownloadCompliancePdf(User $user): bool
    {
        return (bool) $this->getCurrentPlan($user)->limit('pdf_download');
    }

    /**
     * Check if the user can access course certificates.
     */
    public function canAccessCertificates(User $user): bool
    {
        return (bool) $this->getCurrentPlan($user)->limit('certificates');
    }

    /**
     * Get a summary of subscription data for sharing via Inertia.
     *
     * @return array{plan: string, plan_label: string, limits: array<string, int|bool>, usage: array<string, int>, can: array<string, bool>}
     */
    public function getSubscriptionSummary(User $user): array
    {
        $plan = $this->getCurrentPlan($user);
        $billingUser = $this->getBillingUser($user);

        $subscription = $billingUser->subscription('default');
        $isActive = $subscription && $subscription->active();
        $onGracePeriod = $subscription && $subscription->onGracePeriod();

        $student = $user->isStudent() ? $user : $user;

        return [
            'plan' => $plan->value,
            'plan_label' => $plan->label(),
            'is_grandfathered' => $billingUser->grandfathered,
            'is_active' => $isActive,
            'on_grace_period' => $onGracePeriod,
            'limits' => config("subscription.plans.{$plan->value}.limits"),
            'usage' => [
                'ai_questions_this_hour' => $this->getHourlyQuestionCount($student),
                'students' => $billingUser->students()->count(),
                'compliance_reports_this_month' => ComplianceReport::where('parent_id', $billingUser->id)
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->count(),
            ],
            'can' => [
                'ask_question' => $this->canAskQuestion($user),
                'enroll_in_course' => $user->isStudent() ? $this->canEnrollInCourse($user) : true,
                'generate_report' => $this->canGenerateReport($user),
                'add_student' => $this->canAddStudent($user),
                'download_pdf' => $this->canDownloadCompliancePdf($user),
                'access_certificates' => $this->canAccessCertificates($user),
            ],
        ];
    }

    /**
     * Get this hour's question count for a specific student.
     */
    private function getHourlyQuestionCount(User $student): int
    {
        $billingUser = $student->isStudent() ? $student->parent : $student;

        return (int) DailyQuestionCount::where('user_id', $student->id)
            ->where('date', now($billingUser->timezone)->toDateString())
            ->where('hour', now($billingUser->timezone)->hour)
            ->value('count');
    }
}
