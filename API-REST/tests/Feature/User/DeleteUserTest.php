<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_user(): void
    {
        $admin = $this->actingAsAdmin();
        
        $user = User::factory()->create();
        
        $response = $this->deleteJson("/api/users/{$user->id}");
        
        $response->assertStatus(204);
        
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_admin_cannot_delete_non_existent_user(): void
    {
        $admin = $this->actingAsAdmin();
        
        $nonExistentId = 9999;
        
        $response = $this->deleteJson("/api/users/{$nonExistentId}");
        
        $response->assertStatus(404);
    }
}