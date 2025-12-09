<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_refresh_token_successfully()
    {
        
        Http::fake([
            env('APP_URL') . '/oauth/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
                'access_token' => 'new-access-token',
                'refresh_token' => 'new-refresh-token',
            ], 200),
        ]);

        $response = $this->postJson('/api/refresh-token', [
            'refresh_token' => 'old-refresh-token',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'statusCode',
            'message',
            'data' => [
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ],
        ]);

        $this->assertEquals('new-access-token', $response->json('data.access_token'));
    }
}
