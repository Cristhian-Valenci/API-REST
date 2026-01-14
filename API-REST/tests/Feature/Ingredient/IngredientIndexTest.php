<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Ingredient;
use App\Models\User;

class IngredientIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_ingredients()
    {
        
        $user = User::factory()->create();
        

        
        Ingredient::create(['name' => 'Vodka', 'user_id' => $user->id]);
        Ingredient::create(['name' => 'Gin', 'user_id' => $user->id]);

        $response = $this->getJson('/api/ingredients');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data')
                 ->assertJsonFragment(['name' => 'Vodka'])
                 ->assertJsonFragment(['name' => 'Gin']);
    }

    public function test_ingredient_cannot_list_when_no_ingredients_exists()
    {
        $user = User::factory()->create();
        

        $response = $this->getJson('/api/ingredients');

        $response->assertStatus(200);       
    }
}
