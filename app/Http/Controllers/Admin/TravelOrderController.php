<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Http\Services\TravelOrder\TravelOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TravelOrderController
 *
 * This controller handles the management of travel orders.
 */
class TravelOrderController
{
    public function __construct(private TravelOrderService $travelOrderService){}

    /**
     * Store a new travel order.
     *
     * @param CreateTravelOrderRequest $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'destination']);
        $perPage = $request->get('per_page', 10);

        return $this->travelOrderService->listAll($filters, $perPage);

    }

    /**
     * Show a specific travel order.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        return $this->travelOrderService->find($id);

    }
    
    /**
     * Update the status of a travel order.
     *
     * @param string $id
     * @param UpdateTravelOrderStatusRequest $request
     * @return JsonResponse
     */
    public function updateStatus(string $id, UpdateTravelOrderStatusRequest $request): JsonResponse
    {
        $status = $request->validated()['status'];

        return $this->travelOrderService->updateStatus($id, $status);
    }
}
