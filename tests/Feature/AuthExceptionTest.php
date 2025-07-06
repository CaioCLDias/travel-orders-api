<?php

namespace Tests\Feature\Auth;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Services\Auth\AuthService;

class AuthExceptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_throws_exception()
    {
        JWTAuth::shouldReceive('attempt')->andThrow(new \Exception('Simulated exception'));

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(500)
            ->assertJsonFragment([
                'message' => 'An error occurred while processing your request.'
            ]);
    }

    public function test_logout_throws_exception()
    {
        JWTAuth::shouldReceive('getToken')->andThrow(new \Exception('Fail on getToken'));

        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(500)
            ->assertJsonFragment([
                'message' => 'An error occurred while logging out.'
            ]);
    }

    public function test_refresh_throws_exception()
    {
        JWTAuth::shouldReceive('getToken')->andThrow(new \Exception('Simulated refresh error'));

        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/refresh');

        $response->assertStatus(500)
            ->assertJsonFragment([
                'message' => 'An error occurred while refreshing the token.'
            ]);
    }
}
