<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class TrackTimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seconds' => 'required|integer|min:1|max:3600',
            'session_id' => 'nullable|integer',
        ];
    }
}
