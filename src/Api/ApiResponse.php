<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiResponse
{
    public static function success(mixed $data = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];

        return new JsonResponse($response, $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, array $details = []): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => [
                'message' => $message,
                'details' => $details,
            ],
        ];

        return new JsonResponse($response, $statusCode);
    }
}
