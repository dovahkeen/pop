<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'  => 'string|required',
            'password'  => 'string|required|min:6',
            'full_name' => 'string',
            'grade'     => 'int|required|between:0,12'
        ];
    }
}
