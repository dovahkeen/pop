<?php

namespace App\Http\Requests\Period;

use Illuminate\Foundation\Http\FormRequest;

class PeriodStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string',
            'teacher_id'    => 'nullable|int'
        ];
    }
}
