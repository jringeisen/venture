<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'username' => ['required', 'string', 'min:3', 'max:255', Rule::unique(Student::class)->ignore($this->student->id)],
            'password' => 'nullable|string|min:8|max:255|confirmed',
            'password_confirmation' => 'nullable|string|min:8|max:255|required_with:password',
            'grade' => 'required|numeric|min:1|max:12',
            'age' => 'required|numeric|min:5|max:19',
            'timezone' => 'required|string|max:255',
        ];
    }
}
