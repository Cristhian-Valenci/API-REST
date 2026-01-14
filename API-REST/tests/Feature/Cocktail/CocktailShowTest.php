<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cocktail;

class CocktailShowTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_show_returns_cocktail_with_ingredients()
    {
        $cocktail = Cocktail::factory()->withIngredients()->create();

        $response = $this->getJson("/api/cocktails/{$cocktail->id}");

        $response->assertStatus(200);

        $cocktailJson = $response->json('data');

        $this->assertArrayHasKey('id', $cocktailJson);
        $this->assertArrayHasKey('name', $cocktailJson);
        $this->assertArrayHasKey('ingredients', $cocktailJson);
        $this->assertNotEmpty($cocktailJson['ingredients']);

        foreach ($cocktailJson['ingredients'] as $ingredient) {
            $this->assertArrayHasKey('id', $ingredient);
            $this->assertArrayHasKey('name', $ingredient);
            $this->assertArrayHasKey('amount', $ingredient);
            $this->assertArrayHasKey('unit', $ingredient);
        }
    }

   
    public function test_show_returns_404_for_nonexistent_cocktail()
    {
        $response = $this->getJson("/api/cocktails/9999");

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Cocktail not found.',
                 ]);
    }
}
