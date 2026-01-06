<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cocktail;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;


class AuthorizationRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_do_everything()
    {

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $ingredient = Ingredient::factory()->create([
    'user_id' => $admin->id
]);

        $cocktail = Cocktail::factory()->create();

        // Cocktail update (must include at least one ingredient)
        $response = $this->actingAs($admin, 'api')
            ->putJson("/api/cocktails/{$cocktail->id}", [
                'name' => 'Admin Updated',
                'description' => 'Desc',
                'elaboration_method' => 'Method',
                'ingredients' => [
                    [
                        'ingredient_id' => $ingredient->id,
                        'amount' => 10,
                        'unit' => 'cl',
                    ]
                ]
            ]);
        $response->assertStatus(200);

        // Cocktail delete
        $response = $this->actingAs($admin, 'api')
            ->deleteJson("/api/cocktails/{$cocktail->id}");
        $response->assertStatus(204);

        // Ingredient update
        $response = $this->actingAs($admin, 'api')
            ->putJson("/api/ingredients/{$ingredient->id}", [
                'name' => 'Admin Ingredient',
            ]);
        $response->assertStatus(200);

        // Ingredient delete
        $response = $this->actingAs($admin, 'api')
            ->deleteJson("/api/ingredients/{$ingredient->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'message' => 'Ingredient deleted successfully'
                 ]);
    }

    public function test_verified_user_can_only_modify_their_own_resources()
    {

        $user = User::factory()->create();
        $user->assignRole('verified');

        $myIngredient = Ingredient::factory()->create(['user_id' => $user->id]);
        $myCocktail = Cocktail::factory()->create(['user_id' => $user->id]);

        $otherCocktail = Cocktail::factory()->create();
        $otherIngredient = Ingredient::factory()->create();

        // Can update own cocktail
        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$myCocktail->id}", [
                'name' => 'Updated by verified',
                'description' => 'Desc',
                'elaboration_method' => 'Method',
                'ingredients' => [
                    [
                        'ingredient_id' => $myIngredient->id,
                        'amount' => 5,
                        'unit' => 'cl',
                    ]
                ]
            ]);
        $response->assertStatus(200);

        // Cannot update other's cocktail
        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$otherCocktail->id}", [
                'name' => 'Should Fail',
                'description' => 'Desc',
                'elaboration_method' => 'Method',
                'ingredients' => [
                    [
                        'ingredient_id' => $otherIngredient->id,
                        'amount' => 5,
                        'unit' => 'cl',
                    ]
                ]
            ]);
        $response->assertStatus(403);

        // Cannot update other's ingredient
        $response = $this->actingAs($user, 'api')
            ->putJson("/api/ingredients/{$otherIngredient->id}", [
                'name' => 'Should Fail',
            ]);
        $response->assertStatus(403);
    }

    public function test_unverified_user_can_only_view()
    {

        $user = User::factory()->create(); 
        $cocktail = Cocktail::factory()->create();

        // Can view cocktail
        $response = $this->actingAs($user, 'api')
            ->getJson("/api/cocktails/{$cocktail->id}");
        $response->assertStatus(200);

        // Cannot update cocktail
        $ingredient = Ingredient::factory()->create();
        $response = $this->actingAs($user, 'api')
            ->putJson("/api/cocktails/{$cocktail->id}", [
                'name' => 'Should Fail',
                'description' => 'Desc',
                'elaboration_method' => 'Method',
                'ingredients' => [
                    [
                        'ingredient_id' => $ingredient->id,
                        'amount' => 5,
                        'unit' => 'cl',
                    ]
                ]
            ]);
        $response->assertStatus(403);

        // Cannot delete cocktail
        $response = $this->actingAs($user, 'api')
            ->deleteJson("/api/cocktails/{$cocktail->id}");
        $response->assertStatus(403);
    }
}
