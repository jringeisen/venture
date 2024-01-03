<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'username' => 'required|string|min:3|max:255|unique:students,username',
            'password' => 'required|string|min:8|max:255|confirmed',
            'password_confirmation' => 'required|string|min:8|max:255',
            'grade' => 'required|numeric|min:1|max:12',
            'age' => 'required|numeric|min:5|max:19',
            'timezone' => 'required|string|max:255',
        ];
    }
}
