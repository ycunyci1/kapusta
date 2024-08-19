<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Kapusta api",
 *      description="Documentation Kapusta API",
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="Авторизация и регистрация"
 * )
 * @OA\Server(
 *      url="http://31.128.46.70",
 *      description="Debug test server"
 * )
 */
abstract class Controller
{
    public function responseJson(array $data, ?int $code = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'success' => true,
        ], $code);
    }

    public function responseValidationError(string $error, ?array $errors = []): JsonResponse
    {
        return response()->json([
            'error' => $error,
            'errors' => $errors,
            'success' => false
        ], 422);
    }

    public function errorResponse(string $error, ?int $code = 400): JsonResponse
    {
        return response()->json([
            'error' => $error,
            'success' => false
        ], 400);
    }
}
