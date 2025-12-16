<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cocktail;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cocktail>
 */
class CocktailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Cocktail::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->paragraph(),
            'elaboration_method' => $this->faker->text(200),
            'user_id' => User::factory(),
        ];
    }

        public function withIngredients($ingredients = null)
    {
        return $this->afterCreating(function (Cocktail $cocktail) use ($ingredients) {
            $ingredients = $ingredients ?? \App\Models\Ingredient::factory(2)->create();
            foreach ($ingredients as $ingredient) {
                $cocktail->ingredients()->attach($ingredient->id, [
                    'amount' => rand(1, 10),
                    'unit' => 'cl',
                ]);
            }
        });
    }

}
