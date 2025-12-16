<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ingredient;
use App\Models\Cocktail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientUpdateAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_owner_can_update_ingredient()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $ingredient = Ingredient::factory()->create([
            'user_id' => $user->id,
            'name' => 'Vodka',
        ]);

        $response = $this->putJson("/api/ingredients/{$ingredient->id}", [
            'name' => 'Tequila',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Tequila']);

        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
            'name' => 'Tequila',
            'user_id' => $user->id,
        ]);
    }
}