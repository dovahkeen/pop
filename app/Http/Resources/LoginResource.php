<?php

namespace App\Http\Resources;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/* @mixin Student|Teacher */
class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this->full_name,
            'token'     => $this->tokens()->first()
        ];
    }
}
