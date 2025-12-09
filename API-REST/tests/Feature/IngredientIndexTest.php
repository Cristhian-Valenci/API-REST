<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ingredient;


class IngredientIndexTest extends TestCase
{
    use RefreshDatabase;
  
    public function test_index_returns_all_ingredients()
    {
        
        Ingredient::create(['name' => 'Vodka']);
        Ingredient::create(['name' => 'Gin']);

        $response = $this->getJson('/api/ingredients');

        $response->assertStatus(200)
                 ->assertJsonCount(2) 
                 ->assertJsonFragment(['name' => 'Vodka'])
                 ->assertJsonFragment(['name' => 'Gin']);
    }

    public function test_ingredient_cannot_list_when_no_ingredients_exists()
    {
        $response = $this->getJson('/api/ingredients');

        $response->assertStatus(204);
                
    }

}