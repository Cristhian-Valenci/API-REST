<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IngredientCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_an_ingredient()
    {
        $payload = [
            'name' => 'Vodka',
        ];

        $response = $this->postJson('/ingredients', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name' => 'Vodka',
                 ]);

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Vodka',
        ]);
    }
}

