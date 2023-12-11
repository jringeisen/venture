<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => 'required|string',
        ];
    }
}
