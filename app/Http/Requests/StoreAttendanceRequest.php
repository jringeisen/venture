<?php

namespace App\Http\Requests;

use App\Enums\AttendanceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $studentId = $this->input('student_id');

        if (! $studentId) {
            return true;
        }

        return $this->user()->students()->where('id', $studentId)->exists();
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'type' => ['required', Rule::enum(AttendanceType::class)],
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
