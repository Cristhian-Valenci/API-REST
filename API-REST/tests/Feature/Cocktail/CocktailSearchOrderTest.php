<?php

namespace Tests\Feature\Cocktail;

use App\Models\Cocktail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CocktailSearchOrderTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_can_order_cocktails_by_name_asc()
    {
        Cocktail::factory()->create(['name' => 'Zombi']);
        Cocktail::factory()->create(['name' => 'Margarita']);
        Cocktail::factory()->create(['name' => 'Bloody Mary']);

        $response = $this->getJson('/api/cocktails/search?order=name&direction=asc');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Bloody Mary')
            ->assertJsonPath('data.1.name', 'Margarita')
            ->assertJsonPath('data.2.name', 'Zombi');
    }

   
    public function test_can_order_cocktails_by_name_desc()
    {
        Cocktail::factory()->create(['name' => 'Zombi']);
        Cocktail::factory()->create(['name' => 'Margarita']);
        Cocktail::factory()->create(['name' => 'Bloody Mary']);

        $response = $this->getJson('/api/cocktails/search?order=name&direction=desc');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Zombi')
            ->assertJsonPath('data.1.name', 'Margarita')
            ->assertJsonPath('data.2.name', 'Bloody Mary');
    }

    
    public function test_can_order_cocktails_by_creation_date_asc()
    {
        $old = Cocktail::factory()->create(['name' => 'Old cocktail']);
        sleep(1); // Esto es para pausar un segundo el ceated_at y que no se me creen los 2 a la vez
        $new = Cocktail::factory()->create(['name' => 'New cocktail']);

        $response = $this->getJson('/api/cocktails/search?order=created_at&direction=asc');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Old cocktail')
            ->assertJsonPath('data.1.name', 'New cocktail');
    }

    
    public function test_can_order_cocktails_by_creation_date_desc()
    {
        $old = Cocktail::factory()->create(['name' => 'Old cocktail']);
        sleep(1);
        $new = Cocktail::factory()->create(['name' => 'New cocktail']);

        $response = $this->getJson('/api/cocktails/search?order=created_at&direction=desc');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'New cocktail')
            ->assertJsonPath('data.1.name', 'Old cocktail');
    }

    
    public function test_user_can_order_favorite_cocktails_first()
    {
        $user = User::factory()->create();

        $favorite = Cocktail::factory()->create(['name' => 'Margarita']);
        $normal = Cocktail::factory()->create(['name' => 'Negroni']);

        $user->favoriteCocktails()->attach($favorite->id);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/cocktails/search?order=favorites');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Margarita')
            ->assertJsonPath('data.1.name', 'Negroni');
    }
}
