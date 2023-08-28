<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function responseJson(Collection|AnonymousResourceCollection $values): JsonResponse
    {
        return response()->json($values);
    }

    public function responseFailed(Exception $exception): JsonResponse
    {
        return response()->json($exception, Response::HTTP_BAD_REQUEST);
    }

    public function responseSuccess(int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['message' => 'success'], $status);
    }
}
