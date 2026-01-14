<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ingredient;

class IngredientDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_ingredient()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $ingredient = Ingredient::factory()->create([
            'name' => 'Vodka',
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/ingredients/{$ingredient->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'message' => 'Ingredient deleted successfully.'
                 ]);

        $this->assertDatabaseMissing('ingredients', [
            'id' => $ingredient->id
        ]);
    }

    public function test_non_owner_cannot_delete_ingredient()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $ingredient = Ingredient::factory()->create([
            'name' => 'Vodka',
            'user_id' => $owner->id,
        ]);

        $this->actingAs($otherUser, 'api');

        $response = $this->deleteJson("/api/ingredients/{$ingredient->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
            'name' => 'Vodka',
            'user_id' => $owner->id,
        ]);
    }

    public function test_guest_cannot_delete_ingredient()
    {
        $ingredient = Ingredient::factory()->create([
            'name' => 'Vodka',
        ]);

        $response = $this->deleteJson("/api/ingredients/{$ingredient->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
        ]);
    }

    public function test_delete_non_existing_ingredient()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $nonExistentId = Ingredient::max('id') + 1;

        $response = $this->deleteJson("/api/ingredients/{$nonExistentId}");

        $response->assertStatus(404);
    }
}
