<?php

namespace App\Http\Resources;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/* @mixin Teacher */
class TeacherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'fullName'  => $this->full_name,
            'username'  => $this->username,
            'email'     => $this->email,
            'password'  => $this->password,
        ];
    }
}
