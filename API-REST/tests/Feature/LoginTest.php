<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;


class LoginTest extends TestCase
{
    use RefreshDatabase;

   #[Test]
    public function user_can_login_and_get_token()
    {
        
        $user = User::factory()->create([
            'name' => 'Cristhian',
            'email' => 'cristhian@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Simulamos la llamada HTTP a /oauth/token para Passport
        Http::fake([
            env('APP_URL') . '/oauth/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 31536000,
                'access_token' => 'test-access-token',
                'refresh_token' => 'test-refresh-token',
            ], 200),
        ]);

        
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        
        $response->assertStatus(200);

        // Revisar que tenga la estructura de token
        $response->assertJsonStructure([
            'success',
            'statusCode',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
                'token' => [
                    'token_type',
                    'expires_in',
                    'access_token',
                    'refresh_token',
                ],
            ],
        ]);

        
        $this->assertEquals('Cristhian', $response->json('data.name'));
    }

    #[Test]
    public function user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
           'name' => 'Cristhian',
           'email' => 'cristhian@example.com',
           'password' => bcrypt('password123'),
        ]);

    
        Http::fake();

        $response = $this->postJson('/api/login', [
           'email' => $user->email,
           'password' => 'wrong-password',
        ]);

        // Verificamos que la respuesta sea 401
        $response->assertStatus(401);

        // Verificamos la estructura de respuesta para error
        $response->assertJson([
           'success' => true,
           'statusCode' => 401,
           'message' => 'Unauthorized.',
           'errors' => 'Unauthorized',
        ]);
    }

    #[Test]
    public function user_cannot_login_with_empty_password()
    {
        $user = User::factory()->create([
           'email' => 'cristhian@example.com',
           'password' => bcrypt('password123'),
        ]);

        Http::fake();

        $response = $this->postJson('/api/login', [
           'email' => $user->email,
           'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    #[Test]
    public function user_cannot_login_with_unregistered_email()
    {
        Http::fake(); // No necesitamos Passport aquí

        $response = $this->postJson('/api/login', [
            'email' => 'noexiste@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'success' => true,
                    'statusCode' => 401,
                    'message' => 'Unauthorized.',
                    'errors' => 'Unauthorized',
                ]);
    }

}

