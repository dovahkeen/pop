<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\Teacher\TeacherStoreRequest;
use App\Http\Requests\Teacher\TeacherUpdateRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\TeacherResource;
use App\Models\Student;
use App\Models\Teacher;
use App\Traits\Crud;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends Controller
{
    use Crud;

    protected string|Teacher $model = Teacher::class;
    protected string|TeacherResource $resource = TeacherResource::class;

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherStoreRequest $request): JsonResponse
    {
        return $this->saveInstance($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher): JsonResponse
    {
        return $this->responseShowResource($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherUpdateRequest $request, Teacher $teacher): JsonResponse
    {
        return $this->updateInstance($request->validated(), $teacher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher): JsonResponse
    {
        return $this->deleteInstance($teacher);
    }

    /**
     * Handle teacher login.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
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
