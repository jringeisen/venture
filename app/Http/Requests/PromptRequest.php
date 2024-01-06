<?php

namespace App\Http\Requests;

use App\Rules\Moderate;
use Illuminate\Foundation\Http\FormRequest;

class PromptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'question' => ['required', 'string', new Moderate],
        ];
    }
}
