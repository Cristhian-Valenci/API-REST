<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PartialUpdateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_patch_name_only(): void
    {
       $user = User::factory()->create([
          'name' => 'Nombre original',
          'email' => 'original@email.com'
        ]);

        $data = [
           'name' => 'Nombre Actualizado'
        ];

        $response = $this->patchJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                   'name' => 'Nombre Actualizado',
                   'email' => 'original@email.com',
                ]);

        $this->assertDatabaseHas('users', [
           'id' => $user->id,
           'name' => 'Nombre Actualizado',
           'email' => 'original@email.com',
        ]);
}

}
