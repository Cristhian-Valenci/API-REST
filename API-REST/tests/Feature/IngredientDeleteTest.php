<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ingredient;

class IngredientDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_ingredient_successfully()
    {
        $ingredient = Ingredient::create(['name' => 'Vodka']);

        $response = $this->deleteJson("/api/ingredients/{$ingredient->id}");

        $response->assertStatus(200);
                
        $this->assertDatabaseMissing('ingredients', [
            'id' => $ingredient->id
        ]);
    }
}
