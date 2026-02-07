<?php

namespace App\Listeners;

use App\Enums\AttendanceType;
use App\Models\Attendance;
use Illuminate\Auth\Events\Login;

class RecordStudentAttendance
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if (! $user->isStudent()) {
            return;
        }

        $today = now()->timezone($user->timezone)->toDateString();

        Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'type' => AttendanceType::Present,
            ]
        );
    }
}
