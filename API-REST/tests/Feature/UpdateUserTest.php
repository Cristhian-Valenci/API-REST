<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_update(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Nombre actualizado',
            'email' => 'nuevo@email.com'
        ];

        $response = $this->putJson("/api/users/{$user->id}", data);

        $response ->assertStatus(200)
                  ->assertJsonFragment([
                    'name' => 'Nombre Actualizado',
                    'email' => 'nuevo@email.com',
                  ]);

    
        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Nombre Actualizado',
            'email' => 'nuevo@email.com',
        ]);

       
    }
}
