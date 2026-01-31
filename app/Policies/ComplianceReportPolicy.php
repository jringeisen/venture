<?php

namespace App\Policies;

use App\Models\ComplianceReport;
use App\Models\User;

class ComplianceReportPolicy
{
    public function view(User $user, ComplianceReport $complianceReport): bool
    {
        return $user->id === $complianceReport->parent_id
            || $user->isAdmin();
    }

    public function delete(User $user, ComplianceReport $complianceReport): bool
    {
        return $user->id === $complianceReport->parent_id
            || $user->isAdmin();
    }
}
