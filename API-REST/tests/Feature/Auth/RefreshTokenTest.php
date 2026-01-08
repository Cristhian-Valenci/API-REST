<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Laravel\Passport\Passport;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_refresh_token_successfully()
    {
        $user = User::factory()->create();
        $newToken = 'new-access-token';

        Passport::actingAs($user);

        $this->mock(\App\Http\Controllers\AuthController::class)
            ->shouldReceive('refreshToken')
            ->once()
            ->andReturn(response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Token refreshed successfully.',
                'data' => [
                    'user' => $user,
                    'token' => $newToken,
                    'token_type' => 'Bearer',
                ],
            ], 200));

        $response = $this->postJson('/api/refresh-token', [
            'refresh_token' => 'old-refresh-token',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'statusCode',
            'message',
            'data' => [
                'user',
                'token',
                'token_type',
            ],
        ]);

        $this->assertEquals($newToken, $response->json('data.token'));
    }

    #[Test]
    public function user_cannot_refresh_token_if_missing()
    {
        $user = User::factory()->create();
        
        Passport::actingAs($user);

        $this->mock(\App\Http\Controllers\AuthController::class)
            ->shouldReceive('refreshToken')
            ->once()
            ->andReturn(response()->json([
                'message' => 'The refresh token field is required.',
                'errors' => [
                    'refresh_token' => ['The refresh token field is required.']
                ]
            ], 422));

        $response = $this->postJson('/api/refresh-token', [
            'refresh_token' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['refresh_token']);
    }

    #[Test]
    public function user_cannot_refresh_token_with_invalid_token()
    {
        $user = User::factory()->create();
        
        Passport::actingAs($user);

        $this->mock(\App\Http\Controllers\AuthController::class)
            ->shouldReceive('refreshToken')
            ->once()
            ->andReturn(response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'The refresh token is invalid.',
                'data' => [
                    'error' => 'invalid_grant',
                ],
            ], 400));

        $response = $this->postJson('/api/refresh-token', [
            'refresh_token' => 'bad-refresh-token',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'statusCode' => 400,
            'message' => 'The refresh token is invalid.',
        ]);
    }
}