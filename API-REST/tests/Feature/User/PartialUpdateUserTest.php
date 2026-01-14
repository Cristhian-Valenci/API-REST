<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PartialUpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_patch_user_name_only(): void
    {
        $admin = $this->actingAsAdmin();
        
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

    public function test_admin_can_patch_user_email_only(): void
    {
        $admin = $this->actingAsAdmin();
        
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

    public function test_admin_can_patch_user_password_only(): void 
    {
        $admin = $this->actingAsAdmin();
        
        $user = User::factory()->create();
        
        $data = [
            'password' => 'Password123.',
            'password_confirmation' => 'Password123.'
        ];
        
        $response = $this->patchJson("/api/users/{$user->id}", $data);
        
        $response->assertStatus(200);
        
        $this->assertTrue(Hash::check('Password123.', $user->fresh()->password));
    }

    public function test_admin_cannot_patch_user_with_invalid_data(): void
    {
        $admin = $this->actingAsAdmin();
        
        $user = User::factory()->create();
        
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different'
        ];
        
        $response = $this->patchJson("/api/users/{$user->id}", $data);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}