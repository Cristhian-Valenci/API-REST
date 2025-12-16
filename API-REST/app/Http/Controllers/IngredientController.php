<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Http\Requests\Ingredient\StoreIngredientRequest;
use App\Http\Requests\Ingredient\UpdateIngredientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IngredientController extends Controller
{
    use AuthorizesRequests;
    
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
            'user_id' => auth()->id(),
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

        public function update(UpdateIngredientRequest $request, int $id): JsonResponse
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response()->json([
                'message' => 'Ingredient not found'
            ], 404);
        }

        $this->authorize('update', $ingredient);

        $ingredient->update([
            'name' => $request->name,
        ]);

        return response()->json($ingredient, 200);
    }

        public function destroy(Ingredient $ingredient)
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        
        if ($ingredient->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $ingredient->delete();

        return response()->json([
            'message' => 'Ingredient deleted successfully'
        ], 200);
    }


}
