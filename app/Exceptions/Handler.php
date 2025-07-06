<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof ValidationException) {
                throw new HttpResponseException(
                    ApiResponse::error('Validation failed.', 422, $exception->errors())
                );
            }

            if ($exception instanceof ApiRulesException) {
                throw new HttpResponseException(
                    ApiResponse::error($exception->getMessage(), $exception->getStatus())
                );
            }

            if ($exception instanceof HttpExceptionInterface) {
                throw new HttpResponseException(
                    ApiResponse::error($exception->getMessage(), $exception->getStatusCode())
                );
            }

            throw new HttpResponseException(
                ApiResponse::error(
                    config('app.debug') ? $exception->getMessage() : 'Internal server error',
                    500
                )
            );
        }

        return parent::render($request, $exception);
    }
}
