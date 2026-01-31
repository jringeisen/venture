<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionSwapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isParent()
            && $this->user()->subscribed('default');
    }

    public function rules(): array
    {
        return [
            'plan' => 'required|string|in:explorer,family',
            'billing_cycle' => 'required|string|in:monthly,yearly',
        ];
    }
}
