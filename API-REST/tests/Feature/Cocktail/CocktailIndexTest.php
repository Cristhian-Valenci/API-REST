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
        $cocktails = Cocktail::factory()->count(2)->withIngredients()->create();

        $response = $this->getJson('/api/cocktails');

        $response->assertStatus(200)
                ->assertJsonCount(13, 'data');

        $cocktailsJson = $response->json('data');

        foreach ($cocktailsJson['data'] as $cocktail) {
            $this->assertArrayHasKey('id', $cocktail);
            $this->assertArrayHasKey('name', $cocktail);
            $this->assertArrayHasKey('ingredients', $cocktail);
            $this->assertNotEmpty($cocktail['ingredients']);

            foreach ($cocktail['ingredients'] as $ingredient) {
                $this->assertArrayHasKey('id', $ingredient);
                $this->assertArrayHasKey('name', $ingredient);
                $this->assertArrayHasKey('amount', $ingredient);
                $this->assertArrayHasKey('unit', $ingredient);
            }
        }
    }


    public function test_index_returns_200_when_no_cocktails_exist()
    {
        $response = $this->getJson('/api/cocktails');

        

        $response->assertStatus(200)
         ->assertJson([
             'success' => true,
             'message' => 'No cocktails found.',
             'data' => []
         ]);

    }


}
