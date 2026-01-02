<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cocktail;

class CocktailIndexTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_index_returns_all_cocktails_with_ingredients()
    {
        
        $cocktails = Cocktail::factory()
            ->count(2)
            ->withIngredients()
            ->create();

        $response = $this->getJson('/api/cocktails');

        $response->assertStatus(200)
                 ->assertJsonCount(2) 
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'name',
                         'description',
                         'elaboration_method',
                         'user_id',
                         'created_at',
                         'updated_at',
                         'ingredients' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'pivot' => [
                                     'cocktail_id',
                                     'ingredient_id',
                                     'amount',
                                     'unit'
                                 ]
                             ]
                         ]
                     ]
                 ]);
    }
}
