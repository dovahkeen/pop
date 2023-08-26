<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            $this->getModel()->fill($values)->save();
            return $this->responseSuccess();
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
        $modelName = class_basename(Str::singular(Str::replace(class_basename(self::class), null, static::class)));
        return "App\\Models\\$modelName";
    }
}
