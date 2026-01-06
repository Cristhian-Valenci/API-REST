<?php

namespace Tests\Feature\Cocktail;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cocktail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CocktailDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_cocktail()
    {
        $user = User::factory()->create();
        $user->assignRole('verified');

        $cocktail = Cocktail::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $cocktail->user_id);

        $response = $this->actingAs($user, 'api')
            ->deleteJson("/api/cocktails/{$cocktail->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('cocktails', [
            'id' => $cocktail->id,
        ]);
    }


    public function test_user_cannot_delete_cocktail_they_do_not_own()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $cocktail = Cocktail::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($user, 'api')
                         ->deleteJson("/api/cocktails/{$cocktail->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('cocktails', [
            'id' => $cocktail->id,
        ]);
    }
}
