<?php

namespace Tests\Feature\Cocktail;

use App\Models\User;
use App\Models\Cocktail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CocktailFavoriteTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_guest_cannot_favorite_a_cocktail()
    {
        $cocktail = Cocktail::factory()->create();

        $response = $this->postJson("/api/cocktails/{$cocktail->id}/favorite");

        $response->assertStatus(401);
    }

    
    public function test_user_can_favorite_a_cocktail()
    {
        $user = User::factory()->create();
        $cocktail = Cocktail::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->postJson("/api/cocktails/{$cocktail->id}/favorite");

        $response->assertStatus(200);

        $this->assertDatabaseHas('cocktail_user_favorites', [
            'user_id' => $user->id,
            'cocktail_id' => $cocktail->id,
        ]);
    }

    
    public function test_user_cannot_favorite_same_cocktail_twice()
    {
        $user = User::factory()->create();
        $cocktail = Cocktail::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson("/api/cocktails/{$cocktail->id}/favorite");

        $response = $this->actingAs($user, 'api')
            ->postJson("/api/cocktails/{$cocktail->id}/favorite");

        $response->assertStatus(409);
    }

    
    public function test_user_can_unfavorite_a_cocktail()
    {
        $user = User::factory()->create();
        $cocktail = Cocktail::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson("/api/cocktails/{$cocktail->id}/favorite");

        $response = $this->actingAs($user, 'api')
            ->deleteJson("/api/cocktails/{$cocktail->id}/favorite");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('cocktail_user_favorites', [
            'user_id' => $user->id,
            'cocktail_id' => $cocktail->id,
        ]);
    }


}
