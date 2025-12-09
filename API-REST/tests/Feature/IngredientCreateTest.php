<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ingredient;


class IngredientCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_an_ingredient()
    {
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
        ]);
    }

        public function test_cannot_create_ingredient_without_name()
    {
        $payload = []; 
        
        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

        public function test_cannot_create_ingredient_with_name_too_long()
    {
        $payload = [
            'nombre' => str_repeat('a', 101), 
        ];
        
        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

        public function test_cannot_create_ingredient_with_invalid_characters()
    {
        $payload = [
            'nombre' => 'Vodka123!',
        ];
        
        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

        public function test_cannot_create_ingredient_with_duplicate_name()
    {
        
        Ingredient::create(['name' => 'Vodka']);

        $payload = ['name' => 'Vodka'];

        $response = $this->postJson('/api/ingredients', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }


}

