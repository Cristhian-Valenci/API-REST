<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_deleted(): void
    {
     
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_cannot_delete_non_existent_user(): void
    {
        $nonExistentId = User::max('id') + 1;

        $response = $this->deleteJson("/api/users/{$nonExistentId}");

        $response->assertStatus(404);
    }
}
