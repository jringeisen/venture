<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:newsletter_lists,email',
        ];
    }
}
