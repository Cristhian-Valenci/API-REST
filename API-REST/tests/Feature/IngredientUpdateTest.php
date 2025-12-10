<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ingredient;


class IngredientUpdateTest extends TestCase
{
    use RefreshDatabase;


    public function test_update_ingredient_successfully()
    {
        $ingredient = Ingredient::create(['name' => 'Vodka']);

        $payload = ['name' => 'Rum'];

        $response = $this->putJson("/api/ingredients/{$ingredient->id}", $payload);

        $response->assertStatus(200)
                ->assertJsonFragment(['name' => 'Rum']);
    }

}
