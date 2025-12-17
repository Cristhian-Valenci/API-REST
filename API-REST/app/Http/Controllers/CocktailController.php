<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cocktail;

class CocktailController extends Controller
{
    public function store(Request $request)
    {
        $cocktail = Cocktail::create([
            'name' => $request->name,
            'description' => $request->description,
            'elaboration_method' => $request->elaboration_method,
            'user_id' => auth()->id(),
        ]);

        if ($request->has('ingredients')) {
            foreach ($request->ingredients as $ingredient) {
                $cocktail->ingredients()->attach(
                    $ingredient['ingredient_id'],
                    [
                        'amount' => $ingredient['amount'],
                        'unit' => $ingredient['unit'],
                    ]
                );
            }
        }

        return response()->json([
            'id' => $cocktail->id,
            'name' => $cocktail->name,
        ], 201);

    }
}
