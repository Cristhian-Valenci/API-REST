<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IngredientShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_returns_ingredient()
    {
        $ingredient = Ingredient::create(['name' => 'Vodka']);

        $response = $this->getJson("/api/ingredients/{$ingredient->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Vodka'
                 ]);
    }

    public function test_show_for_nonexistent_ingredient()
    {
        $response = $this->getJson("/api/ingredients/999");

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Ingredient not found'
                 ]);
    }
}