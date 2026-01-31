<?php

namespace App\Http\Requests;

use App\Enums\ComplianceState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateComplianceReportRequest extends FormRequest
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
            'state' => ['required', Rule::enum(ComplianceState::class)],
            'period_start' => 'required|date|before_or_equal:period_end',
            'period_end' => 'required|date|after_or_equal:period_start|before_or_equal:today',
            'title' => 'nullable|string|max:255',
        ];
    }
}
