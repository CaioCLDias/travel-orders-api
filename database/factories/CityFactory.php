<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            'state' => $this->faker->state,
            'uf' => strtoupper($this->faker->lexify('??')),
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
            'uf_code' => $this->faker->numberBetween(1, 99),
            'ibge_code' => $this->faker->numberBetween(1000000, 9999999),
        ];
    }
}
