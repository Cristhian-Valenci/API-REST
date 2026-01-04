<?php

namespace Tests\Feature\Cocktail;

use App\Models\Cocktail;
use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CocktailSearchTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_can_search_cocktails_by_name()
    {
        Cocktail::factory()->create(['name' => 'Mojito']);
        Cocktail::factory()->create(['name' => 'Margarita']);

        $response = $this->getJson('/api/cocktails/search?name=mojito');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'Mojito',
            ]);
    }

    
    public function test_can_search_cocktails_by_ingredient()
    {
        $vodka = Ingredient::factory()->create(['name' => 'Vodka']);
        $rum   = Ingredient::factory()->create(['name' => 'Rum']);

        $withVodka = Cocktail::factory()->create(['name' => 'Vodka Drink']);
        $withRum   = Cocktail::factory()->create(['name' => 'Rum Drink']);

        $withVodka->ingredients()->attach($vodka->id, [
            'amount' => 50,
            'unit' => 'ml',
        ]);

        $withRum->ingredients()->attach($rum->id, [
            'amount' => 50,
            'unit' => 'ml',
        ]);

        $response = $this->getJson('/api/cocktails/search?ingredient=vodka');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'Vodka Drink',
            ]);
    }

   
    public function test_guest_cannot_search_favorite_cocktails()
    {
        $response = $this->getJson('/api/cocktails/search?favorite=1');

        $response->assertStatus(401);
    }

    
    public function test_user_can_search_only_favorite_cocktails()
    {
        $user = User::factory()->create();

        $favorite = Cocktail::factory()->create(['name' => 'Favorite Cocktail']);
        $notFavorite = Cocktail::factory()->create(['name' => 'Normal Cocktail']);

        $user->favorites()->attach($favorite->id);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/cocktails/search?favorite=1');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'Favorite Cocktail',
            ]);
    }
}
