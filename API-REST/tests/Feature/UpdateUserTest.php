<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Update name',
            'email' => 'new@email.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $response ->assertStatus(200)
                  ->assertJsonFragment([
                    'name' => 'Update name',
                    'email' => 'new@email.com',
                ]);

    
        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Update name',
            'email' => 'new@email.com',
          
        ]);

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
             

       
    }
}
