<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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
}
