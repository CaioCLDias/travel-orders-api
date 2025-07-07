<?php

namespace App\Http\Services\Auth;

use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * Class AuthService
 * Handles authentication services such as login, logout, refresh token, and user retrieval.
 * @package App\Http\Services
 */
class AuthService
{

    public function login(array $credentials): JsonResponse
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return ApiResponse::error('Credenciais invalidas', 401);
            }

            return ApiResponse::success([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 'Login realizado com sucesso.');
        } catch (\Exception $e) {
            return ApiResponse::error('Ocorreu um erro ao efetuar Login', 500, $e->getMessage());
        }
    }


    /**
     * Retrieve the currently authenticated user.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            return ApiResponse::success(
                Auth::user(),
                'Usuario encontrado com sucesso.'
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Ocorreu um erro ao encontrar usuário', 500, $e->getMessage());
        }
    }

    /**
     * Logout the currently authenticated user by invalidating their token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return ApiResponse::success(null, 'Logout successful.');
        } catch (\Exception $e) {
            return ApiResponse::error('Ocorreu um erro ao realizar o logout.', 500, $e->getMessage());
        }
    }

    /**
     * Refresh the JWT token for the currently authenticated user.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {

            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return ApiResponse::success([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 'Token refreshed successfully.');
            
        } catch (\Exception $e) {
            return ApiResponse::error('Ocorreu um erro ao renovar o token', 500, $e->getMessage());
        }
    }
}
