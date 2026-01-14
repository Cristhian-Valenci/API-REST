<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_show_user_by_id(): void
    {
        $admin = $this->actingAsAdmin();
        
        $user = User::factory()->create();
        
        $response = $this->getJson("/api/users/{$user->id}");
        
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $user->id,
                     'email' => $user->email                      
                 ]);
    }

    public function test_admin_cannot_show_user_because_id_not_exist(): void
    {
        $admin = $this->actingAsAdmin();
        
        $nonExistentId = 9999;
        
        $response = $this->getJson("/api/users/{$nonExistentId}");
        
        $response->assertStatus(404);
    }
}