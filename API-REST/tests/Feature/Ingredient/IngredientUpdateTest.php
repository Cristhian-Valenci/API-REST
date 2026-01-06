<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Ingredient;
use App\Models\User;

class IngredientUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function createUserAndIngredient($ingredientName = 'Vodka')
    {
        $user = User::factory()->create();

        $ingredient = Ingredient::create([
            'name' => $ingredientName,
            'user_id' => $user->id
        ]);

        return [$user, $ingredient];
    }

    public function test_update_ingredient_successfully()
    {
        [$user, $ingredient] = $this->createUserAndIngredient();
        $user->assignRole('verified');

        $payload = ['name' => 'Rum'];

        $response = $this->actingAs($user, 'api')
                         ->putJson("/api/ingredients/{$ingredient->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Rum']);
    }

    public function test_update_nonexistent_ingredient()
    {
        $user = User::factory()->create();
        $nonExistentId = Ingredient::max('id') + 1;

        $response = $this->actingAs($user, 'api')
                         ->putJson("/api/ingredients/{$nonExistentId}", [
                             'name' => 'NonExistentIngredient'
                         ]);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Ingredient not found']);
    }

    public function test_update_without_name()
    {
        [$user, $ingredient] = $this->createUserAndIngredient();

        $response = $this->actingAs($user, 'api')
                         ->putJson("/api/ingredients/{$ingredient->id}", [
                             'name' => ''
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');
    }

    public function test_update_with_duplicate_name()
    {
        [$user, $ingredient1] = $this->createUserAndIngredient('Vodka');
        [$user, $ingredient2] = $this->createUserAndIngredient('Tequila');

        $response = $this->actingAs($user, 'api')
                         ->putJson("/api/ingredients/{$ingredient2->id}", [
                             'name' => 'Vodka'
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');
    }

    public function test_update_with_invalid_characters()
    {
        [$user, $ingredient] = $this->createUserAndIngredient();

        $response = $this->actingAs($user, 'api')
                         ->putJson("/api/ingredients/{$ingredient->id}", [
                             'name' => 'Vodka123!!'
                         ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');
    }
}
