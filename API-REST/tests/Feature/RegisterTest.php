<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

     #[Test]
    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Cristhian',
            'email' => 'cristhian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
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
                'token',
            ],
        ]);
    }

    #[Test]
    public function user_cannot_register_with_existing_email()
    {
        User::factory()->create([
            'email' => 'cristhian@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Otro User with same email',
            'email' => 'cristhian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

     #[Test]
    public function user_cannot_register_if_passwords_do_not_match()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Cristhian',
            'email' => 'cristhian2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

     #[Test]
    public function user_cannot_register_with_invalid_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Cristhian',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

        #[Test]
    public function user_cannot_register_with_empty_password()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Cristhian',
            'email' => 'cristhian3@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

        #[Test]
    public function user_cannot_register_with_invalid_name()
    {
        
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'cristhian4@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        
        $response = $this->postJson('/api/register', [
            'name' => str_repeat('a', 300), //HAgo que el nombre tenga 300 a para probar nombre largo
            'email' => 'cristhian5@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

}
