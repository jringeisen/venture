<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function view(User $user, Attendance $attendance): bool
    {
        return $user->students()->where('id', $attendance->user_id)->exists()
            || $user->isAdmin();
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->students()->where('id', $attendance->user_id)->exists()
            || $user->isAdmin();
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->students()->where('id', $attendance->user_id)->exists()
            || $user->isAdmin();
    }
}
