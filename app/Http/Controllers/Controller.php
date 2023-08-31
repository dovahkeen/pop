<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Generate a JSON response with a custom message and status code.
     *
     * This method generates a JSON response with the provided message and optional HTTP status code.
     *
     * @param string $string The message to include in the response.
     * @param int $status Optional. The HTTP status code for the response. Default is 200 (OK).
     * @return JsonResponse
     */
    public function response(string $string, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['message' => $string], $status);
    }

    /**
     * Generate a JSON response from a collection or resource.
     *
     * This method generates a JSON response from a collection, anonymous resource collection, or JSON resource.
     *
     * @param Collection|AnonymousResourceCollection|JsonResource $values The data to include in the response.
     * @return JsonResponse
     */
    public function responseJson(Collection|AnonymousResourceCollection|JsonResource $values): JsonResponse
    {
        return response()->json($values);
    }

    /**
     * Generate a JSON response for handling failure cases.
     *
     * This method generates a JSON response with information from the provided exception,
     * indicating a failure, along with an HTTP status code indicating a bad request.
     *
     * @param Exception $exception The exception representing the failure.
     * @return JsonResponse
     */
    public function responseFailed(Exception $exception): JsonResponse
    {
        return response()->json($exception, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Generate a JSON response indicating a successful operation.
     *
     * This method generates a JSON response with a success message and an optional HTTP status code.
     * The default status code is 200 (OK).
     *
     * @param int $status Optional. The HTTP status code for the response. Default is 200 (OK).
     * @return JsonResponse
     */
    public function responseSuccess(int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['message' => 'success'], $status);
    }
}
