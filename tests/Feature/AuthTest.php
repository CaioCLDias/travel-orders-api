<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => ['access_token', 'token_type', 'expires_in']
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized();
    }

    public function test_unauthenticated_user_cannot_access_protected_route(): void
    {
        $response = $this->getJson('/api/orders');
        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_protected_route(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/orders');

        $response->assertOk();
    }

    public function test_user_can_refresh_token(): void
    {
        $user = User::factory()->create();

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/refresh');

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => ['access_token', 'token_type', 'expires_in']
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Logout successful.']);
    }

    public function test_me_returns_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/me');

        $response->assertOk()
            ->assertJsonFragment(['email' => $user->email]);
    }

    public function test_refresh_without_token_returns_unauthorized(): void
    {
        $response = $this->postJson('/api/refresh');

        $response->assertStatus(401);
    }
}
