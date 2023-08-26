<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeriodStoreRequest;
use App\Models\Period;
use App\Traits\Crud;
use Illuminate\Http\JsonResponse;

class PeriodController extends Controller
{
    use Crud;

    public function getByTeacher(int $teacherId): JsonResponse
    {
        $result = Period::query()->byTeacher($teacherId)->paginate();

        return $this->responseJson($result);
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeriodStoreRequest $request, Period $period)
    {
        $this->updateInstance($request->validated(), $period);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
