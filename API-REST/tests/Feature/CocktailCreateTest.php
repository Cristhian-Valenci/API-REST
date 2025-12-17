<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class CocktailCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_cocktail(): void
    {
        $payload = [
            'name' => 'Margarita',
            'description' => 'Classic mexican cocktail',
            'elaboration_method' => 'Shake with ice and serve on martini glass',
            'ingredients' => []
        ];

        $response = $this->postJson('/api/cocktails', $payload);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_cocktail()
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Margarita',
            'description' => 'Classic cocktail',
            'elaboration_method' => 'Shake with ice',
            'ingredients' => []
        ];

        $response = $this->actingAs($user, 'api')
                        ->postJson('/api/cocktails', $payload);

        $response->assertStatus(201);
    }


}
