<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTravelOrderRequest;
use App\Http\Resources\TravelOrderResource;
use App\Http\Services\TravelOrder\TravelOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TravelOrderController
{
    public function __construct(private TravelOrderService $travelOrderService) {}

    /**
     * Store a new travel order.
     *
     * @param CreateTravelOrderRequest $request
     * @return JsonResponse
     */
    public function store(CreateTravelOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userId = auth()->id();

        return $this->travelOrderService->create($data, $userId);

    }

    /**
     * List travel orders for the authenticated user with optional filters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $filters = $request->only(['status', 'date_from', 'date_to', 'destination']);
        $perPage = $request->get('per_page', 10);

        return $this->travelOrderService->listForUser($userId, $filters, $perPage);

    }

    /**
     * Show a specific travel order for the authenticated user.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $userId = auth()->id();

        return $this->travelOrderService->findForUser($id, $userId);

    }

    
}
