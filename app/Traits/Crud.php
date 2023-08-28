<?php

namespace App\Traits;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

trait Crud
{
    /**
     * Display a listing of the resource.
     * Method getModel will return a model by the requested controller name.
     * index will return a paginated list
     */
    public function index(): JsonResponse
    {
        $results = $this->getModel()::query()->paginate();
        $resource = $this->getResource();
        return $this->responseJson($resource::collection($results));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateInstance(array $values, Model $model): JsonResponse
    {
        try {
            $model->update($values);
            return $this->responseSuccess();
        } catch (Exception $exception) {
            return $this->responseFailed($exception);
        }
    }

    public function responseShowResource(Model $model): JsonResponse
    {
        $resource = $this->getResource();
        return response()->json(new $resource($model));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function saveInstance(array $values): JsonResponse
    {
        try {
            $model = $this->getModel();
            $modelInstance = new $model($values);
            $modelInstance->save();
            return $this->responseSuccess(Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->responseFailed($exception);
        }
    }

    private function getResource(): string|JsonResource
    {
        $modelName = str_replace(class_basename(self::class), null, class_basename(static::class));
        return "App\\Http\\Resources\\{$modelName}Resource";
    }

    private function getModel(): string|Model
    {
        return $this->model;
    }
}
