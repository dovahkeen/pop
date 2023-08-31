<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait Crud
 *
 * This trait provides common CRUD (Create, Read, Update, Delete) operations for resources using Laravel.
 * It encapsulates methods for handling typical CRUD operations, such as listing, updating, deleting, and creating resources.
 * The trait assumes certain conventions in terms of model and resource class names, and it provides consistent error handling and response formatting.
 *
 */
trait Crud
{
    /**
     * Display a listing of the resource.
     * Method getModel will return a model by the requested controller name.
     * index will return a paginated list
     */
    public function index(): JsonResponse
    {
        $results = $this->getModel()::query()->get();
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

    /**
     * Remove the specified resource from storage.
     */
    public function deleteInstance(Model $model): JsonResponse
    {
        try {
            $model->delete();
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
        return $this->resource;
    }

    private function getModel(): string|Model
    {
        return $this->model;
    }
}
