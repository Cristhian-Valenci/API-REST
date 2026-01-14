<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\User;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar el usuario admin por email
        $admin = User::where('email', 'admin@example.com')->first();

        $ingredients = [
            'Ron blanco',
            'Ron oscuro',
            'Vodka',
            'Tequila',
            'Ginebra',
            'Campari',
            'Cachaca',
            'Vermouth Rosso',
            'Licor de café',
            'Vermouth Bianco',
            'Aperol',
            'Prosseco',
            'Triple Sec',
            'Zumo de limón',
            'Zumo de lima',
            'Zumo de naranja',
            'Zumo de arándanos',
            'Zumo de pomelo',
            'Zumo de tomate',
            'Azúcar',
            'Soda',
            'Menta',
            'Angostura',
            'Ginger beer',
            'Tonica',
            'Coca Cola',
            'Sal',
            'Pimienta',
            'Salsa Perrins',
            'Pisco',
            'Whiskey'
        ];

        foreach ($ingredients as $ingredientName) {
            Ingredient::create([
                'name' => $ingredientName,
                'user_id' => $admin->id,
            ]);
        }
    }
}