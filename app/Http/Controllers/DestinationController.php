<?php

namespace App\Http\Controllers;

use App\Http\Services\Destination\DestinationService;
use Illuminate\Http\JsonResponse;

/**
 * Class DestinationController
 * Handles requests related to destinations, such as listing states and cities.
 */
class DestinationController
{
    public function __construct(private DestinationService $destinationService){}

    /**
     * List all states.
     *
     * @return JsonResponse
     */
    public function listStates(): JsonResponse
    {
        return $this->destinationService->listSates();
    }
    
    /**
     * List cities by state IBGE code.
     *
     * @param string $stateIbgeCode
     * @return JsonResponse
     */
    public function listCitiesByState(string $stateIbgeCode): JsonResponse
    {
        return $this->destinationService->listCitiesByState($stateIbgeCode);
    }
}
