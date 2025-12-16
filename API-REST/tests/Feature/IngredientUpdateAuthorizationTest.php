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
        $this->actingAs($user, 'api');

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

    public function test_non_owner_cannot_update_ingredient()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $ingredient = Ingredient::factory()->create([
            'user_id' => $owner->id,
            'name' => 'Vodka',
        ]);

        $this->actingAs($otherUser, 'api');

        $response = $this->putJson("/api/ingredients/{$ingredient->id}", [
            'name' => 'Tequila',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
            'name' => 'Vodka', 
            'user_id' => $owner->id,
        ]);
    }

    public function test_guest_cannot_update_ingredient()
    {
        $ingredient = Ingredient::factory()->create([
            'name' => 'Vodka',
        ]);

        $response = $this->putJson("/api/ingredients/{$ingredient->id}", [
            'name' => 'Tequila',
        ]);

        $response->assertStatus(401);

        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
            'name' => 'Vodka',
        ]);
    }

    public function test_owner_cannot_update_ingredient_used_by_other_users_cocktail()
    {
        
        $ingredientOwner = User::factory()->create();
        $ingredient = Ingredient::factory()->create([
            'user_id' => $ingredientOwner->id,
            'name' => 'Vodka',
        ]);

        $cocktailOwner = User::factory()->create();
        $cocktail = \App\Models\Cocktail::factory()
            ->for($cocktailOwner, 'user') 
            ->withIngredients([$ingredient])  
            ->create();

        
        $this->actingAs($ingredientOwner, 'api');
        $response = $this->putJson("/api/ingredients/{$ingredient->id}", [
            'name' => 'Tequila',
        ]);

        $response->assertStatus(403);

        
        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
            'name' => 'Vodka',
            'user_id' => $ingredientOwner->id,
        ]);
    }




}