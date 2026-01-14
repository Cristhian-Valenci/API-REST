<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ListUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users(): void
    {
        $admin = $this->actingAsAdmin();
        
        $users = User::factory()->count(2)->create();
        
        $response = $this->getJson('/api/users');
        
        $response->assertStatus(200)
                 ->assertJsonCount(3); // 2 usuarios + 1 admin
    }

    public function test_list_returns_only_admin_when_no_other_users_exist(): void 
    {
        $admin = $this->actingAsAdmin();
        
        $response = $this->getJson('/api/users');
        
        $response->assertStatus(200)
                 ->assertJsonCount(1); // Solo el admin
    }
}