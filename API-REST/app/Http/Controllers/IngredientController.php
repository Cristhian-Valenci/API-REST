<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Http\Requests\Ingredient\StoreIngredientRequest;
use Illuminate\Http\JsonResponse;

class IngredientController extends Controller
{
    public function index(): JsonResponse
    {
        $ingredients = Ingredient::all(); 

        if($ingredients->isEmpty()) {
            return response()->json([],204);
        }

        return response()->json($ingredients, 200);
    }

   
    public function store(StoreIngredientRequest $request)
    {
        
        $ingredient = Ingredient::create([
            'name' => $request->name,
        ]);

        return response()->json($ingredient, 201);
    }

    public function show(int $id): JsonResponse
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response()->json([
                'message' => 'Ingredient not found'
            ], 404);
        }

        return response()->json($ingredient, 200);
    }

        public function update(Request $request, int $id): JsonResponse
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response()->json([
                'message' => 'Ingredient not found'
            ], 404);
        }

        $ingredient->update([
            'name' => $request->name,
        ]);

        return response()->json($ingredient, 200);
    }

}
