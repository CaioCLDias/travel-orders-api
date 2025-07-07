<?php

namespace App\Http\Services\TravelOrder;

use App\Http\Resources\TravelOrderResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\Notifications\TravelOrderNotificationService;
use App\Models\TravelOrder;
use Illuminate\Http\JsonResponse;

/**
 * Class TravelOrderService
 *
 * This service handles the creation of travel orders.
 */
class TravelOrderService
{

    /**
     * Create a new travel order for a user.
     *
     * @param array $data
     * @param string $userId
     * @return JsonResponse
     */
    public function create(array $data, int $userId): JsonResponse
    {

        try {
            $travelOrder = TravelOrder::create([
                'user_id' => $userId,
                'city_id' => $data['city_id'],
                'departure_date' => $data['departure_date'],
                'return_date' => $data['return_date'],
                'status' => 'pending',
            ]);


            return ApiResponse::success(
                new TravelOrderResource($travelOrder),
                'Ordem de viagem criada com sucesso.',
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Ocorreu um erro ao criar a ordem de viagem: ',
                500,
                $e->getMessage()
            );
        }
    }

    /**
     * List travel orders for a specific user with optional filters.
     *
     * @param string $userId
     * @param array $filters
     * @return JsonResponse
     */
    public function listForUser(int $userId, array $filters = []): JsonResponse
    {
        try {
            $query = TravelOrder::with(['destination.stateRelation', 'user'])->where('user_id', $userId);

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['destination'])) {
                $query->whereHas('destination', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['destination'] . '%');
                });
            }

            if (isset($filters['from'])) {
                $query->where('departure_date', '>=', $filters['date_from']);
            }

            if (isset($filters['date_to'])) {
                $query->where('return_date', '<=', $filters['date_to']);
            }

            $travelOrders = $query->get();

            return  ApiResponse::success(
                TravelOrderResource::collection($travelOrders),
                'Ordens de viagem consultadas com sucesso.'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Ocorreu um erro ao consultar as ordens de viagem: ',
                500,
                $e->getMessage()
            );
        }
    }


    /**
     * Find a specific travel order for a user.
     *
     * @param string $userId
     * @param string $travelOrderId
     * @return JsonResponse
     */
    public function findForUser(int $id, int $userId): JsonResponse
    {
        try {

            $travelOrder = TravelOrder::with(['destination.stateRelation', 'user'])->where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$travelOrder) {
                return ApiResponse::error('Ordem de viagem não encontrada', 404);
            }

            return ApiResponse::success(
                new TravelOrderResource($travelOrder),
                'Ordem de viagem consultada com sucesso.'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Ocorreu um erro ao consultar a ordem de viagem: ',
                500,
                $e->getMessage()
            );
        }
    }

    /**
     * List all travel orders with optional filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return JsonResponse
     */
    public function listAll(array $filters = []): JsonResponse
    {
        try {
            $query = TravelOrder::with(['destination.stateRelation', 'user']);

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['destination'])) {
                $query->whereHas('destination', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['destination'] . '%');
                });
            }

            if (isset($filters['date_from'])) {
                $query->where('departure_date', '>=', $filters['date_from']);
            }

            if (isset($filters['date_to'])) {
                $query->where('return_date', '<=', $filters['date_to']);
            }

            $travelOrders = $query->get();

            return  ApiResponse::success(
                TravelOrderResource::collection($travelOrders),
                'Ordens de viagem consultadas com sucesso.'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Aconteceu um erro ao consultar as ordens de viagem: ',
                500,
                $e->getMessage()
            );
        }
    }

    /**
     * Find a specific travel order by ID.
     *
     * @param string $travelOrderId
     * @return JsonResponse
     */
    public function find(int $travelOrderId): JsonResponse
    {
        try {

            $travelOrder = TravelOrder::with(['destination.stateRelation', 'user'])->find($travelOrderId);

            if (!$travelOrder) {
                return ApiResponse::error('Ordem de viagem não encontrada', 404);
            }

            return ApiResponse::success(
                new TravelOrderResource($travelOrder),
                'Ordem de viagem consultada com sucesso.'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Um erro aconteceu ao consultar a ordem de viagem. ',
                500,
                $e->getMessage()
            );
        }
    }

    /**
     * Update the status of a travel order.
     *
     * @param string $id
     * @param string $newStatus
     * @return JsonResponse
     */
    public function updateStatus(int $id, string $newStatus): JsonResponse
    {
        try {
            $travelOrder = TravelOrder::with('user')->find($id);

            if (!$travelOrder) {
                return ApiResponse::error('Ordem de Viagem não encontrada', 404);
            }

            if ($travelOrder->status === 'approved' && $newStatus === 'cancelled') {
                return ApiResponse::error('Não pode Cancelar uma venda já aprovadada', 400);
            }

            $travelOrder->status = $newStatus;
            $travelOrder->save();

            $statusLabel = $newStatus === 'approved' ? 'aprovada' : 'cancelada';
            $travelOrder->user->notify(
                new TravelOrderNotificationService($travelOrder, $statusLabel)
            );

            return ApiResponse::success(
                new TravelOrderResource($travelOrder),
                'Travel order status updated successfully.'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'An error occurred while updating the travel order status: ',
                500,
                $e->getMessage()
            );
        }
    }
}
