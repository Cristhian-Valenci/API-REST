<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $ingredient = Ingredient::create([
            'name' => $request->name,
        ]);

        return response()->json($ingredient, 201);
    }
}
