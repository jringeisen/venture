<?php

namespace App\Http\Controllers\Student;

use App\Enums\AttendanceType;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = now()->timezone($user->timezone)->toDateString();

        Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'type' => AttendanceType::FieldTrip,
                'created_by' => $user->id,
            ]
        );

        return back();
    }
}
