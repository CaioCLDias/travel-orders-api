<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    protected $model = State::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->state,
            'uf' => strtoupper($this->faker->lexify('??')),
            'ibge_code' => $this->faker->unique()->numberBetween(10, 99), // Deve bater com uf_code da city
        ];
    }
}
