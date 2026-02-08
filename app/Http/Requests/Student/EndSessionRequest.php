<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class EndSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id' => 'required|integer',
            'final_seconds' => 'nullable|integer|min:0',
        ];
    }
}
