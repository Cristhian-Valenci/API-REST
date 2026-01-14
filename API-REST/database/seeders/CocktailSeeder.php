<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cocktail;
use App\Models\Ingredient;
use App\Models\User;

class CocktailSeeder extends Seeder
{
    public function run()
    {
        
        $admin = User::where('email', 'admin@example.com')->first();

        $cocktails = [
            [
                'name' => 'Mojito',
                'description' => 'Refreshing Cuban cocktail with mint, lime and rum.',
                'elaboration_method' => 'Muddle the mint, mix with rum, ice and soda.',
                'ingredients' => [
                    ['name' => 'White rum', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Lime juice', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Sugar', 'amount' => 2, 'unit' => 'spoon'],
                    ['name' => 'Mint', 'amount' => 10, 'unit' => 'units'],
                    ['name' => 'Soda', 'amount' => 100, 'unit' => 'ml'],
                ],
            ],
            [
                'name' => 'Aperol Spritz',
                'description' => 'Classic Italian, refreshing and light.',
                'elaboration_method' => 'Mix Aperol, prosecco and a splash of soda.',
                'ingredients' => [
                    ['name' => 'Aperol', 'amount' => 60, 'unit' => 'ml'],
                    ['name' => 'Prosecco', 'amount' => 90, 'unit' => 'ml'],
                    ['name' => 'Soda', 'amount' => 30, 'unit' => 'ml'],
                ],
            ],
            [
                'name' => 'Negroni',
                'description' => 'Bitter and intense, perfect as an aperitif.',
                'elaboration_method' => 'Mix equal parts gin, vermouth rosso and Campari.',
                'ingredients' => [
                    ['name' => 'Gin', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Vermouth Rosso', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Campari', 'amount' => 30, 'unit' => 'ml'],
                ],
            ],
            [
                'name' => 'Cosmopolitan',
                'description' => 'Elegant and fruity cocktail.',
                'elaboration_method' => 'Shake vodka, triple sec, cranberry juice and lime.',
                'ingredients' => [
                    ['name' => 'Vodka', 'amount' => 40, 'unit' => 'ml'],
                    ['name' => 'Triple Sec', 'amount' => 15, 'unit' => 'ml'],
                    ['name' => 'Cranberry juice', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Lime juice', 'amount' => 10, 'unit' => 'ml'],
                ],
            ],
            [
                'name' => 'Whiskey Sour',
                'description' => 'Classic with whiskey and lemon.',
                'elaboration_method' => 'Shake whiskey, lemon juice and sugar with ice.',
                'ingredients' => [
                    ['name' => 'Whiskey', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Lemon juice', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Sugar', 'amount' => 2, 'unit' => 'spoon'],
                ],
            ],
            [
                'name' => 'Margarita',
                'description' => 'Classic Mexican cocktail with tequila and lime.',
                'elaboration_method' => 'Shake tequila, triple sec and lime juice with ice.',
                'ingredients' => [
                    ['name' => 'Tequila', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Triple Sec', 'amount' => 20, 'unit' => 'ml'],
                    ['name' => 'Lime juice', 'amount' => 30, 'unit' => 'ml'],
                ],
            ],
            [
                'name' => 'Daiquiri',
                'description' => 'Refreshing, simple and classic.',
                'elaboration_method' => 'Shake rum, lemon juice and sugar with ice.',
                'ingredients' => [
                    ['name' => 'White rum', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Lemon juice', 'amount' => 25, 'unit' => 'ml'],
                    ['name' => 'Sugar', 'amount' => 2, 'unit' => 'spoon'],
                ],
            ],
            [
                'name' => 'Gin Tonic',
                'description' => 'Classic refreshing gin and tonic.',
                'elaboration_method' => 'Serve gin with ice and top with tonic.',
                'ingredients' => [
                    ['name' => 'Gin', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Tonic', 'amount' => 150, 'unit' => 'ml'],
                ],
            ],
            [
                'name' => 'Cuba Libre',
                'description' => 'Refreshing with rum and cola.',
                'elaboration_method' => 'Serve rum with Coca Cola and ice, add lime.',
                'ingredients' => [
                    ['name' => 'White rum', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Coca Cola', 'amount' => 120, 'unit' => 'ml'],
                    ['name' => 'Lime juice', 'amount' => 10, 'unit' => 'ml'],
                ],
            ],
            [
                'name' => 'Bloody Mary',
                'description' => 'Classic vodka and tomato cocktail.',
                'elaboration_method' => 'Mix vodka, tomato juice, lemon and spices.',
                'ingredients' => [
                    ['name' => 'Vodka', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Lemon juice', 'amount' => 15, 'unit' => 'ml'],
                    ['name' => 'Sugar', 'amount' => 1, 'unit' => 'spoon'],
                    ['name' => 'Tomato juice', 'amount' => 120, 'unit' => 'ml'],
                    ['name' => 'Worcestershire sauce', 'amount' => 1, 'unit' => 'spoon'],
                    ['name' => 'Salt', 'amount' => 1, 'unit' => 'spoon'],
                    ['name' => 'Pepper', 'amount' => 1, 'unit' => 'spoon'],
                ],
            ],
            [
                'name' => 'Mai Tai',
                'description' => 'Exotic, fruity and rum-based.',
                'elaboration_method' => 'Mix rums, triple sec, almond syrup and lime.',
                'ingredients' => [
                    ['name' => 'White rum', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Dark rum', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Triple Sec', 'amount' => 15, 'unit' => 'ml'],
                    ['name' => 'Lime juice', 'amount' => 15, 'unit' => 'ml'],
                    ['name' => 'Sugar', 'amount' => 1, 'unit' => 'spoon'],
                ],
            ],
            [
                'name' => 'Tequila Sunrise',
                'description' => 'Colorful cocktail with tequila and orange juice.',
                'elaboration_method' => 'Serve tequila, orange juice and grenadine.',
                'ingredients' => [
                    ['name' => 'Tequila', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Orange juice', 'amount' => 100, 'unit' => 'ml'],
                    ['name' => 'Sugar', 'amount' => 1, 'unit' => 'spoon'],
                ],
            ],
            [
                'name' => 'Pisco Sour',
                'description' => 'Peruvian cocktail with pisco and lemon.',
                'elaboration_method' => 'Shake pisco, lemon juice and sugar with egg white.',
                'ingredients' => [
                    ['name' => 'Pisco', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Lemon juice', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Sugar', 'amount' => 2, 'unit' => 'spoon'],
                ],
            ],
            [
                'name' => 'Caipirinha',
                'description' => 'Brazilian cocktail with cachaça and lime.',
                'elaboration_method' => 'Muddle lime with sugar, add cachaça and ice.',
                'ingredients' => [
                    ['name' => 'Cachaça', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Lime juice', 'amount' => 30, 'unit' => 'ml'],
                    ['name' => 'Sugar', 'amount' => 2, 'unit' => 'spoon'],
                ],
            ],
            [
                'name' => 'Moscow Mule',
                'description' => 'Refreshing, with vodka and ginger beer.',
                'elaboration_method' => 'Serve vodka with ginger beer and lime juice over ice.',
                'ingredients' => [
                    ['name' => 'Vodka', 'amount' => 50, 'unit' => 'ml'],
                    ['name' => 'Ginger beer', 'amount' => 120, 'unit' => 'ml'],
                    ['name' => 'Lime juice', 'amount' => 15, 'unit' => 'ml'],
                ],
            ],
        ];

        
        foreach ($cocktails as $c) {
            $cocktail = Cocktail::create([
                'name' => $c['name'],
                'description' => $c['description'],
                'elaboration_method' => $c['elaboration_method'],
                'user_id' => $admin->id, 
            ]);

            foreach ($c['ingredients'] as $i) {
                $ingredient = Ingredient::firstOrCreate(['name' => $i['name']]);
                $cocktail->ingredients()->attach($ingredient->id, [
                    'amount' => $i['amount'],
                    'unit' => $i['unit'],
                ]);
            }
        }
    }
}
