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
        $ingredient = Ingredient::create(['nombre' => 'Vodka']);

        $response = $this->getJson("/api/ingredients/{$ingredient->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Vodka'
                 ]);
    }
}