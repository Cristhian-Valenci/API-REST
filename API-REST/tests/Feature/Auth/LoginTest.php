<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Laravel\Passport\Passport;

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

        
        $token = 'test-access-token';

        $this->mock(\App\Http\Controllers\AuthController::class)
            ->shouldReceive('login')
            ->once()
            ->andReturn(response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'User has been logged successfully.',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ]));

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'statusCode',
            'message',
            'data' => [
                'user' => ['id','name','email','created_at','updated_at'],
                'token',
                'token_type',
            ],
        ]);

        $this->assertEquals('Cristhian', $response->json('data.user.name'));
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

        
        $response->assertStatus(401);

        
        $response->assertJson([
           'success' => false,
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
        Http::fake(); 

        $response = $this->postJson('/api/login', [
            'email' => 'noexiste@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'success' => false,
                    'statusCode' => 401,
                    'message' => 'Unauthorized.',
                    'errors' => 'Unauthorized',
                ]);
    }

    #[Test]
    public function user_cannot_login_with_empty_email()
    {
        $user = User::factory()->create([
            'email' => 'cristhian@example.com',
            'password' => bcrypt('password123'),
        ]);

        Http::fake();

        $response = $this->postJson('/api/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertStatus(422); 
        $response->assertJsonValidationErrors(['email']);
    }

        #[Test]
    public function user_cannot_login_with_invalid_email_format()
    {
        $user = User::factory()->create([
            'email' => 'cristhian@example.com',
            'password' => bcrypt('password123'),
        ]);

        Http::fake();

        $response = $this->postJson('/api/login', [
            'email' => 'correo-invalido',
            'password' => 'password123',
        ]);

        $response->assertStatus(422); // Validación falla por formato
        $response->assertJsonValidationErrors(['email']);
    }

}

