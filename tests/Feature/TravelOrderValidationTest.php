<?php

namespace Tests\Feature\TravelOrder;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelOrderValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_requires_all_fields()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson('/api/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'city_id',
                'departure_date',
                'return_date',
            ]);
    }

    public function test_departure_date_must_be_a_valid_date()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson('/api/orders', [
            'destination' => 'São Paulo',
            'departure_date' => 'not-a-date',
            'return_date' => '2025-08-01',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['departure_date']);
    }

    public function test_return_date_must_be_a_valid_date()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson('/api/orders', [
            'destination' => 'São Paulo',
            'departure_date' => '2025-08-01',
            'return_date' => 'invalid-date',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['return_date']);
    }

    public function test_departure_date_cannot_be_in_the_past()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson('/api/orders', [
            'destination' => 'São Paulo',
            'departure_date' => now()->subDay()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['departure_date']);
    }

    public function test_return_date_must_be_after_departure_date()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson('/api/orders', [
            'destination' => 'São Paulo',
            'departure_date' => now()->addDays(5)->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['return_date']);
    }
}
