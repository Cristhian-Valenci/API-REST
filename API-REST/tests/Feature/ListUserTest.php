<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use app\Http\Controllers\UserController;
use App\Http\Requests\UserRequest;
use App\Models\User;


class ListUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list(): void
    {
     
        $users = User::factory()->count(2)->create();

        $response = $this->getJson('/api/users');
      
        $response->assertStatus(200)
                 ->assertJsonCount(2);

    }
}
