<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Http\Requests\Ingredient\StoreIngredientRequest;

class IngredientController extends Controller
{
   
    public function store(StoreIngredientRequest $request)
    {
        
        $ingredient = Ingredient::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);

        return response()->json($ingredient, 201);
    }
}
