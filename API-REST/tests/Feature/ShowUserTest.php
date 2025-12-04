<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_show_by_id(): void
    {
       $user = User::factory()->create();

       $response = $this->getJson("/api/users/{$user->id}");

       $response->assertStatus(200)
                ->assertJsonFragment([
                   'id' => $user->id,
                   'email' => $user->email                      
                ]);

    }

    public function test_user_cannot_show_because_id_not_exist(): void
    {
        $nonExistenId = 9999;

        $response = $this->getJson("/api/users/{$nonExistenId}");

        $response->assertStatus(404);
                
    }

 
}
