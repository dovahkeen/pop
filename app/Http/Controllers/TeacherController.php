<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\Teacher\TeacherStoreRequest;
use App\Http\Resources\LoginResource;
use App\Models\Student;
use App\Models\Teacher;
use App\Traits\Crud;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends Controller
{
    use Crud;

    protected string $model = Teacher::class;

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherStoreRequest $request): JsonResponse
    {
        return $this->saveInstance($request->validated());
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::guard('teachers')->attempt($request->validated())) {
            /* @var Student $teacher */
            $teacher = Auth::guard('teachers')->user();
            $teacher->createToken('authToken');

            return $this->responseJson(new LoginResource($teacher));
        }

        return $this->response('Login failed', Response::HTTP_UNAUTHORIZED);
    }
}
