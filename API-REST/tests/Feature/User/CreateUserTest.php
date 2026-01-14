<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use App\Models\User;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'verified', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'unverified', 'guard_name' => 'web']);
        
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'verified', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'unverified', 'guard_name' => 'api']);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = $this->actingAsAdmin();
        
        $response = $this->postJson('/api/users', [
            'name' => 'Prueba',
            'email' => 'prueba@prueba.com',
            'password' => 'Password123.',
            'password_confirmation' => 'Password123.',
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('users', [
            'email' => 'prueba@prueba.com',
        ]);
    }

    public function test_admin_cannot_create_user_with_invalid_data(): void
    {
        $admin = $this->actingAsAdmin();
        
        $response = $this->postJson('/api/users', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}