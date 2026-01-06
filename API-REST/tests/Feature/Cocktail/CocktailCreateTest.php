<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\Cocktail;

class CocktailCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_cocktail(): void
    {
        $ingredient = Ingredient::factory()->create();

        $payload = [
            'name' => 'Margarita',
            'description' => 'Classic mexican cocktail',
            'elaboration_method' => 'Shake with ice and serve on martini glass',
            'ingredients' => [
                [
                    'ingredient_id' => $ingredient->id,
                    'amount' => 5,
                    'unit' => 'cl'
                ]
            ]
        ];

        $response = $this->postJson('/api/cocktails', $payload);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_cocktail()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();


        $payload = [
            'name' => 'Margarita',
            'description' => 'Classic cocktail',
            'elaboration_method' => 'Shake with ice',
            'ingredients' => [
                [
                    'ingredient_id' => $ingredient->id,
                    'amount' => 5,
                    'unit' => 'cl'
                ]
            ]
        ];

        $response = $this->actingAs($user, 'api')
                        ->postJson('/api/cocktails', $payload);

        $response->assertStatus(201);
    }

    public function test_can_create_cocktail_with_ingredients() {

        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $payload = [
            'name' => 'Margarita',
            'description' => 'Classic mexican cocktail',
            'elaboration_method' => 'Shake with ice and serve on martini glass',
            'ingredients' => [
                [
                    'ingredient_id' => $ingredient->id,
                    'amount' => 5,
                    'unit' => 'cl'
                ]
            ]
        ];

        $response = $this->actingAs($user, 'api')
                        ->postJson('/api/cocktails', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('cocktails', [
            'name' => strtolower('Margarita'),
            'user_id' => $user->id,
        ]);

        $cocktailId = $response->json('id');

        $this->assertDatabaseHas('cocktail_ingredient', [
            'cocktail_id' => $cocktailId,
            'ingredient_id' => $ingredient->id,
            'amount' => 5,
            'unit' => 'cl',
        ]);

    }


}
