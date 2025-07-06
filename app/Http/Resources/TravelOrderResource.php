<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'city_id' => $this->city_id,
            'departure_date' => $this->departure_date,
            'return_date' => $this->return_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'user' => new AuthResource($this->whenLoaded('user')),
            'destination' => [
                'id' => $this->destination->id,
                'name' => $this->destination->name,
                'uf' => $this->destination->uf,
                'city_ibge_code' => $this->destination->ibge_code,
                'state_ibge_code' => $this->destination->stateRelation->ibge_code,
            ],
        ];
    }
}
