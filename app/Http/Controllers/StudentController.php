<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\Student\StudentStoreRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Traits\Crud;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    use Crud;

    protected string $model = Student::class;
    /**
     * Display a listing of the resource.
     * Method getModel will return a model by the requested controller name.
     * index will return a paginated list
     */
    public function index(?int $periodId, ?int $teacherId): JsonResponse
    {
        $results = Student::query()
            ->when($periodId, fn($q) => $q->byPeriod($periodId))
            ->when($teacherId, fn($q) => $q->beTeacher($teacherId))
            ->paginate();

        return $this->responseJson(StudentResource::collection($results));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login(LoginRequest $request)
    {
        Auth::user();
        $request->user();
        // todo: return a token or something...
        if (Auth::guard('students')->attempt($request->validated())) {
            /* @var Student $student */
            $student = Auth::guard('students')->user();
            $token = $student->createToken('wow', ['actions:allowed']);
//            $token = Auth::tok
//            $request->session()->regenerate();
//
//            return redirect()->intended('dashboard');
        }

    }
}
