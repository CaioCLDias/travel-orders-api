<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'city_id' => City::factory(),
            'departure_date' => now()->addDays(5),
            'return_date' => now()->addDays(10),
            'status' => 'pending',
        ];
    }
}
