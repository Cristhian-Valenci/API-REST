<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Http;



class LogoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_logout()
    {
        
        $user = User::factory()->create();

        
        $token = $user->createToken('Test Token')->accessToken;

        
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/logout');

       
        $response->assertStatus(204);

       
        $this->assertCount(0, $user->tokens);
    }


}
