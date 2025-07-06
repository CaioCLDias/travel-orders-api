<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use App\Http\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * Class AuthController
 * Handles authentication actions such as login, logout, refresh token, and retrieving user information.
 */
class AuthController
{

    public function __construct(private AuthService $authService) {}

    /**
     * Login a user with the provided credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        return $this->authService->login($credentials);
    }

    /**
     * Retrieve the currently authenticated user.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->authService->me();
    }

    /**
     * Logout the currently authenticated user by invalidating their token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->authService->logout();
    }

    /**
     * Refresh the JWT token for the currently authenticated user.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->authService->refresh();
    }
}
