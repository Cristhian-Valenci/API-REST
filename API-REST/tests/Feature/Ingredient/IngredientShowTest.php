<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Ingredient;
use App\Models\User;

class IngredientShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_returns_ingredient()
    {
        $user = User::factory()->create();
       

        $ingredient = Ingredient::create([
            'name' => 'Vodka',
            'user_id' => $user->id
        ]);

        $response = $this->getJson("/api/ingredients/{$ingredient->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Vodka']);
    }

    public function test_show_for_nonexistent_ingredient()
    {
        $user = User::factory()->create();
        

        $response = $this->getJson("/api/ingredients/999");

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Ingredient not found']);
    }
}
