<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PartialUpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_patch_name_only(): void
    {
       $user = User::factory()->create([
          'name' => 'Original name',
          'email' => 'original@email.com'
        ]);

        $data = [
           'name' => 'Update name'
        ];

        $response = $this->patchJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                   'name' => 'Update name',
                   'email' => 'original@email.com',
                ]);

        $this->assertDatabaseHas('users', [
           'id' => $user->id,
           'name' => 'Update name',
           'email' => 'original@email.com',
        ]);
    }

    public function test_user_can_patch_email_only(): void
    {
       $user = User::factory()->create([
          'name' => 'Original name',
          'email' => 'original@email.com'
        ]);

        $data = [
           'email' => 'update@email.com'
        ];

        $response = $this->patchJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                   'name' => 'Original name',
                   'email' => 'update@email.com',
                ]);

        $this->assertDatabaseHas('users', [
           'id' => $user->id,
           'name' => 'Original name',
           'email' => 'update@email.com',
        ]);
    }


    public function test_user_can_patch_password_only(): void 
    {
        $user = User::factory()->create();

        $data = [
           'password' => 'nuevaclave123',
           'password_confirmation' => 'nuevaclave123'
        ];
 
        $response = $this->patchJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200);

        $this->assertTrue(Hash::check('nuevaclave123', $user->fresh()->password));

    }

}
