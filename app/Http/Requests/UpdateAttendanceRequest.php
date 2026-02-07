<?php

namespace App\Http\Requests;

use App\Enums\AttendanceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $attendance = $this->route('attendance');

        return $this->user()->students()->where('id', $attendance->user_id)->exists()
            || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(AttendanceType::class)],
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
