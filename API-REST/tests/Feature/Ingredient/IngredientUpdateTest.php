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

        public function test_update_nonexistent_ingredient()
    {
        $nonExistentId = Ingredient::max('id') + 1;

        $response = $this->putJson("/api/ingredients/{$nonExistentId}", [
            'name' => 'NonExistenIngredient'
        ]);


        $response->assertStatus(404)
                ->assertJson([
                    'message' => 'Ingredient not found'
                ]);
    }

        public function test_update_without_name()
    {
        $ingredient = Ingredient::create(['name' => 'Vodka']);

        $response = $this->putJson("/api/ingredients/{$ingredient->id}", [
            'name' => ''
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors('name');
    }

        public function test_update_with_duplicate_name()
    {
        Ingredient::create(['name' => 'Vodka']);
        $ingredient2 = Ingredient::create(['name' => 'Tequila']);

        $response = $this->putJson("/api/ingredients/{$ingredient2->id}", [
            'name' => 'Vodka'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors('name');
    }

        public function test_update_with_invalid_characters()
    {
        $ingredient = Ingredient::create(['name' => 'Vodka']);

        $response = $this->putJson("/api/ingredients/{$ingredient->id}", [
            'name' => 'Vodka123!!'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors('name');
    }





}
