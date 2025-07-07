<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateUserRequest;
use App\Http\Services\User\UserService;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 *
 * This controller handles user-related operations such as creating a new user.
 */
class UserController
{
    public function __construct(private UserService $travelOrderService) {}

    /**
     * Store a new user.
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        return $this->travelOrderService->createUser($data);
    }

 
}