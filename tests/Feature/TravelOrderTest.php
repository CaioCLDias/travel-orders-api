<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\State;
use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_travel_order(): void
    {
        $user = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        $payload = [
            'city_id' => $city->id,
            'departure_date' => now()->addDays(5)->format('Y-m-d'),
            'return_date' => now()->addDays(10)->format('Y-m-d'),
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/orders', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['city_id' => $city->id]);
    }

    public function test_user_can_list_own_travel_orders(): void
    {
        $user = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        TravelOrder::factory()->count(3)->create([
            'user_id' => $user->id,
            'city_id' => $city->id
        ]);

        $response = $this->actingAs($user, 'api')->getJson('/api/orders');

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_user_can_view_their_own_travel_order(): void
    {
        $user = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id,
        ]);

        $response = $this->actingAs($user, 'api')->getJson("/api/orders/{$order->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $order->id]);
    }

    public function test_user_cannot_view_others_travel_order(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        $order = TravelOrder::factory()->create([
            'user_id' => $otherUser->id,
            'city_id' => $city->id
        ]);

        $response = $this->actingAs($user, 'api')->getJson("/api/orders/{$order->id}");

        $response->assertNotFound();
    }

    public function test_admin_can_approve_travel_order(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id
        ]);

        $response = $this->actingAs($admin, 'api')->putJson("/api/admin/orders/{$order->id}", [
            'status' => 'approved'
        ]);

        $response->assertOk()
            ->assertJsonFragment(['status' => 'approved']);
    }

    public function test_user_cannot_approve_their_own_order(): void
    {
        $user = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id
        ]);

        $response = $this->actingAs($user, 'api')->putJson("/api/admin/orders/{$order->id}", [
            'status' => 'approved'
        ]);

        $response->assertStatus(403);
    }

    public function test_approved_order_cannot_be_cancelled(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id,
            'status' => 'approved'
        ]);

        $response = $this->actingAs($admin, 'api')->putJson("/api/admin/orders/{$order->id}", [
            'status' => 'cancelled'
        ]);

        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Invalid credentials.']);
    }

    public function test_admin_can_filter_travel_orders_by_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        TravelOrder::factory()->create([
            'status' => 'approved',
            'user_id' => $user->id,
            'city_id' => $city->id
        ]);

        TravelOrder::factory()->create([
            'status' => 'cancelled',
            'user_id' => $user->id,
            'city_id' => $city->id
        ]);

        $response = $this->actingAs($admin, 'api')->getJson('/api/admin/orders?status=approved');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['status' => 'approved']);
    }

    public function test_admin_can_view_any_travel_order(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $state = State::factory()->create();
        $city = City::factory()->create(['uf_code' => $state->ibge_code]);

        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'city_id' => $city->id
        ]);

        $response = $this->actingAs($admin, 'api')->getJson("/api/admin/orders/{$order->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $order->id]);
    }

    public function test_admin_gets_404_for_nonexistent_travel_order(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin, 'api')->getJson("/api/admin/orders/9999");

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Travel order not found.']);
    }
}
