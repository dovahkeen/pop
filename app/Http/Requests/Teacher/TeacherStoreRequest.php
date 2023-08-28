<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class TeacherStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'username'  => 'string|required',
            'password'  => 'string|required|min:6',
            'fullName'  => 'string',
            'email'     => 'email|required'
        ];
    }
}
