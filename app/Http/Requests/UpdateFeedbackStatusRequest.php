<?php

namespace App\Http\Requests;

use App\Enums\FeedbackStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeedbackStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(array_column(FeedbackStatuses::cases(), 'value'))],
        ];
    }
}
