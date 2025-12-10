<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ingredient;

class IngredientCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_an_ingredient()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payload = [
            'name' => 'Vodka',
        ];

        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Vodka',
                 ]);

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Vodka',
            'user_id' => $user->id, // <--- importante
        ]);
    }

    public function test_cannot_create_ingredient_without_name()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payload = [];

        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_create_ingredient_with_name_too_long()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payload = [
            'name' => str_repeat('a', 101),
        ];

        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_create_ingredient_with_invalid_characters()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payload = [
            'name' => 'Vodka123!',
        ];

        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_create_ingredient_with_duplicate_name()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Ingredient::create([
            'name' => 'Vodka',
            'user_id' => $user->id,
        ]);

        $payload = ['name' => 'Vodka'];

        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }
}
