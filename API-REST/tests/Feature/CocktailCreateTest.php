<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CocktailCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_cocktail(): void
    {
        $payload = [
            'nombre' => 'Margarita',
            'descripcion' => 'Classic mexican cocktail',
            'metodo_elaboracion' => 'Shake with ice and serve on martini glass',
            'ingredients' => []
        ];

        $response = $this->postJson('/api/cocktails', $payload);

        $response->assertStatus(401);
    }
}
