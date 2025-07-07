<?php

namespace App\Http\Services\Destination;

use App\Http\Responses\ApiResponse;
use App\Models\State;
use Illuminate\Http\JsonResponse;

/**
 * Class DestinationService
 *
 * This service handles the retrieval of states and cities for travel destinations.
 */
class DestinationService
{
    /**
     * List all states.
     *
     * @return JsonResponse
     */
    public function listSates(): JsonResponse
    {
        try {
            $states = State::orderBy('name')->get(['id', 'name', 'uf', 'ibge_code']);
            return ApiResponse::success(
                $states,
                'Estados listados com sucesso.',
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Ocorreu um erro ao listar estados ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * List cities by state IBGE code.
     *
     * @param string $stateIbgeCode
     * @return JsonResponse
     */
    public function listCitiesByState(string $stateIbgeCode): JsonResponse
    {

        try {

            $cities = State::where('ibge_code', $stateIbgeCode)
                ->with(['cities' => function ($query) {
                    $query->orderBy('name');
                }])
                ->first()
                ->cities;

            if ($cities->isEmpty()) {
                return ApiResponse::error(
                    'Nenhuma cidade encontrada para o estado informado.',
                    404
                );
            }

            return ApiResponse::success(
                $cities,
                'Cidades listadas com sucesso.',
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Ocorreu um erro ao listar as cidades ' . $e->getMessage(),
                500
            );
        }
    }
}
