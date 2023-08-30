<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\Student\StudentStoreRequest;
use App\Http\Requests\Student\StudentUpdateRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\StudentResource;
use App\Models\Period;
use App\Models\Student;
use App\Traits\Crud;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    use Crud;

    protected string|Student $model = Student::class;
    protected string|StudentResource $resource = StudentResource::class;

    /**
     * Display a listing of the resource.
     * Method getModel will return a model by the requested controller name.
     * index will return a paginated list
     */
    public function index(int $periodId = null, int $teacherId = null): JsonResponse
    {
        $results = Student::query()
            ->when($periodId, fn($q) => $q->byPeriod($periodId))
            ->when($teacherId, fn($q) => $q->beTeacher($teacherId))
            ->paginate();

        return $this->responseJson(StudentResource::collection($results));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentStoreRequest $request): JsonResponse
    {
        return $this->saveInstance($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(student $student): JsonResponse
    {
        return $this->responseShowResource($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentUpdateRequest $request, Student $student): JsonResponse
    {
        return $this->updateInstance($request->validated(), $student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): JsonResponse
    {
        return $this->deleteInstance($student);
    }

    /**
     * Add a student to a period.
     */
    public function addToPeriod(Student $student, Period $period): JsonResponse
    {
        $student->periods()->attach($period);
        return $this->response('Student added to the period successfully.');
    }

    /**
     * Remove a student to a period.
     */
    public function removeFromPeriod(Student $student, Period $period): JsonResponse
    {
        $student->periods()->detach($period->id);
        return $this->response('Student removed from the period successfully.');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::guard('students')->attempt($request->validated())) {
            /* @var Student $student */
            $student = Auth::guard('students')->user();
            $student->createToken('authToken', ['actions:allowed']);

            return $this->responseJson(new LoginResource($student));
        }

        return $this->response('Login failed', Response::HTTP_UNAUTHORIZED);
    }
}
