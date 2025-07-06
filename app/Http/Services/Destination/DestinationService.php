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
                'States retrieved successfully.',
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'An error occurred while retrieving states: ' . $e->getMessage(),
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
                    'No cities found for the specified state.',
                    404
                );
            }

            return ApiResponse::success(
                $cities,
                'Cities retrieved successfully.',
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'An error occurred while retrieving cities: ' . $e->getMessage(),
                500
            );
        }
    }
}
