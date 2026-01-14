<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_user(): void
    {
        $admin = $this->actingAsAdmin();
        
        $user = User::factory()->create();
        
        $data = [
            'name' => 'Update name',
            'email' => 'new@email.com',
            'password' => 'Password123.',
            'password_confirmation' => 'Password123.'
        ];
        
        $response = $this->putJson("/api/users/{$user->id}", $data);
        
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Update name',
                     'email' => 'new@email.com',
                 ]);
        
        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Update name',
            'email' => 'new@email.com',
        ]);
        
        $this->assertTrue(Hash::check('Password123.', $user->fresh()->password));
    }

    public function test_admin_cannot_update_user_with_invalid_data(): void
    {
        $admin = $this->actingAsAdmin();
        
        $user = User::factory()->create();
        
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ];
        
        $response = $this->putJson("/api/users/{$user->id}", $data);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}