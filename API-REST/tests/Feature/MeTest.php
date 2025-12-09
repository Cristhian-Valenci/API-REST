<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Http;

class MeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_get_own_info()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/me'); //no creo un token real, uso uno falso para el test

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'statusCode',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertEquals($user->email, $response->json('data.email'));
    }


}
