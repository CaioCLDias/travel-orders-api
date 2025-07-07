<?php

namespace App\Http\Services\User;

use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Class UserService
 *
 * This service handles user-related operations such as creating a new user.
 */
class UserService
{

    /**
     * Create a new user.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function createUser(array $data): JsonResponse
    {
        try {
            $user = \App\Models\User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'is_admin' => $data['is_admin'] ?? false,
            ]);

              return ApiResponse::success(
                new UserResource($user),
                'Usuário criado com sucesso.',
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Ocorreu um erro ao criar o usuário: ',
                500,
                $e->getMessage()
            );
        }
    }


}
