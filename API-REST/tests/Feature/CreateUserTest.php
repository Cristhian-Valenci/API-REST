<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Requests\UserRequest;

class CreateUserTest extends TestCase
{
    use RefreshDatabase; // para aplicar migraciones y que se limpie entre test

    public function test_user_can_register(): void
    {
        $data = [
            'name' => 'prueba',
            'email' => 'prueba@prueba.com',
            'password' => 'pruebaprueba',
            'password_confirmation' => 'pruebaprueba',
        ];

        $response = $this->postJson('/api/users', $data); // envia Json y laravel interpreta bien la ruta como API
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'prueba@prueba.com']);
    }

    public function test_user_cannot_register_with_invalid_data(): void
    {
       $data = [
          'name' => '', 
          'email' => 'invalid-email', 
          'password' => 'short',
          'password_confirmation' => 'different'
        ];

       $response = $this->postJson('/api/users', $data);

       $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

}
