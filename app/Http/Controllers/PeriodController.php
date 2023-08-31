<?php

namespace App\Http\Controllers;

use App\Http\Requests\Period\PeriodStoreRequest;
use App\Http\Resources\PeriodResource;
use App\Models\Period;
use App\Traits\Crud;
use Illuminate\Http\JsonResponse;

class PeriodController extends Controller
{
    use Crud;

    protected string|Period $model = Period::class;
    protected string|PeriodResource $resource = PeriodResource::class;

    /**
     * Get periods by teacher.
     *
     * This method retrieves a list of period records associated with the specified teacher.
     *
     * @param int $teacherId The ID of the teacher.
     * @return JsonResponse
     */
    public function getByTeacher(int $teacherId): JsonResponse
    {
        $result = Period::query()->byTeacher($teacherId)->get();

        return $this->responseJson($this->resource::collection($result));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeriodStoreRequest $request): JsonResponse
    {
        return $this->saveInstance($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Period $period): JsonResponse
    {
        return $this->responseShowResource($period);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeriodStoreRequest $request, Period $period): JsonResponse
    {
        return $this->updateInstance($request->validated(), $period);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Period $period): JsonResponse
    {
        return $this->deleteInstance($period);
    }
}
