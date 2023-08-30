<?php

namespace App\Http\Resources;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/* @mixin Student */
class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'username'  => $this->username,
            'fullName'  => $this->full_name,
            'grade'     => $this->grade
        ];
    }

}
