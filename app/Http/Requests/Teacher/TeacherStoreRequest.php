<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class TeacherStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'  => 'required|string',
            'password'  => 'required|string|min:6',
            'full_name' => 'required|string',
            'email'     => 'required|email'
        ];
    }
}
