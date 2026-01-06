<?php

namespace Tests\Feature\Cocktail;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Cocktail;
use App\Models\Ingredient;

class CocktailUpdateTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_guest_cannot_update_cocktail()
    {
        $cocktail = Cocktail::factory()->withIngredients()->create();

        $response = $this->putJson("/api/cocktails/{$cocktail->id}", []);

        $response->assertStatus(401);
    }

    
    public function test_user_cannot_update_cocktail_if_not_owner()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $cocktail = Cocktail::factory()
            ->for($owner)
            ->withIngredients()
            ->create();

        $response = $this->actingAs($otherUser, 'api')
            ->putJson("/api/cocktails/{$cocktail->id}", [
                'name' => 'Updated name',
                'description' => 'New description',
                'elaboration_method' => 'Shake it',
                'ingredients' => [
                    ['ingredient_id' => 1, 'amount' => 50, 'unit' => 'ml']
                ],
            ]);

        $response->assertStatus(403);
    }

    
    public function test_owner_can_update_cocktail()
    {
        $user = User::factory()->create();
        $user->assignRole('verified');
        $ingredient = Ingredient::factory()->create();

        $cocktail = Cocktail::factory()
            ->for($user)
            ->withIngredients()
            ->create();

        $payload = [
            'name' => 'Updated margarita',
            'description' => 'Updated description',
            'elaboration_method' => 'Updated method',
            'ingredients' => [
                [
                    'ingredient_id' => $ingredient->id,
                    'amount' => 10,
                    'unit' => 'cl',
                ]
            ]
        ];

        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$cocktail->id}", $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('cocktails', [
            'id' => $cocktail->id,
            'name' => 'Updated margarita',
        ]);

        $this->assertDatabaseHas('cocktail_ingredient', [
            'cocktail_id' => $cocktail->id,
            'ingredient_id' => $ingredient->id,
            'amount' => 10,
            'unit' => 'cl',
        ]);
    }

   
    public function test_cannot_update_cocktail_without_ingredients()
    {
        $user = User::factory()->create();

        $cocktail = Cocktail::factory()
            ->for($user)
            ->withIngredients()
            ->create();

        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$cocktail->id}", [
                'name' => 'Invalid cocktail',
                'ingredients' => []
            ]);

        $response->assertStatus(422);
    }


  
    public function test_cannot_update_cocktail_with_duplicate_name()
    {
        $user = User::factory()->create();

        $existingCocktail = Cocktail::factory()
            ->for($user)
            ->create([
                'name' => 'margarita',
            ]);

        $cocktailToUpdate = Cocktail::factory()
            ->for($user)
            ->withIngredients()
            ->create([
                'name' => 'mojito',
            ]);

        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$cocktailToUpdate->id}", [
                'name' => 'margarita', 
                'description' => 'Updated',
                'elaboration_method' => 'Updated',
                'ingredients' => [
                    [
                        'ingredient_id' => Ingredient::factory()->create()->id,
                        'amount' => 5,
                        'unit' => 'cl',
                    ]
                ]
            ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors('name');
    }


        
    public function test_can_update_cocktail_with_same_name()
    {
        $user = User::factory()->create();
        $user->assignRole('verified');

        $cocktail = Cocktail::factory()
            ->for($user)
            ->withIngredients()
            ->create([
                'name' => 'margarita',
            ]);

        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$cocktail->id}", [
                'name' => 'margarita',
                'description' => 'Updated',
                'elaboration_method' => 'Updated',
                'ingredients' => [
                    [
                        'ingredient_id' => Ingredient::factory()->create()->id,
                        'amount' => 6,
                        'unit' => 'cl',
                    ]
                ]
            ]);

        $response->assertStatus(200);
    }


    
    public function test_cannot_update_cocktail_with_duplicate_ingredients()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $cocktail = Cocktail::factory()
            ->for($user)
            ->withIngredients()
            ->create();

        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$cocktail->id}", [
                'name' => 'updated',
                'description' => 'Updated',
                'elaboration_method' => 'Updated',
                'ingredients' => [
                    [
                        'ingredient_id' => $ingredient->id,
                        'amount' => 5,
                        'unit' => 'cl',
                    ],
                    [
                        'ingredient_id' => $ingredient->id, // duplicado
                        'amount' => 3,
                        'unit' => 'cl',
                    ]
                ]
            ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors('ingredients');
    }

    
    public function test_cannot_update_cocktail_with_invalid_unit()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $cocktail = Cocktail::factory()
            ->for($user)
            ->withIngredients()
            ->create();

        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$cocktail->id}", [
                'name' => 'updated',
                'description' => 'Updated',
                'elaboration_method' => 'Updated',
                'ingredients' => [
                    [
                        'ingredient_id' => $ingredient->id,
                        'amount' => 5,
                        'unit' => 'kg', // inválido
                    ]
                ]
            ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors('ingredients.0.unit');
    }

}
