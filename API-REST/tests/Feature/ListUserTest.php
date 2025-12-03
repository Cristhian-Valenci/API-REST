<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_list(): void
    {
      $users = User::factory()->count(2)->create();

      $response = $this->getJson('/api/users');
      $response->assertStatus(200);
      $response->assertJsonCount(2);

    }
}
