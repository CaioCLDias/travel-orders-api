<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($data = null, $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error(string $message = 'Error', int $statusCode = 400, mixed $errors = null)
    {
        return response()->json([
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $statusCode);
    }
}
