<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Nombre Actualizado',
            'email' => 'nuevo@email.com'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);

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
