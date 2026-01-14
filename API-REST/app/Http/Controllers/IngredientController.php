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
        
        if ($ingredients->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No ingredients found.',
                'data' => []
            ], 200);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Ingredients retrieved successfully.',
            'data' => $ingredients
        ], 200);
    }

    public function store(StoreIngredientRequest $request): JsonResponse
    {
        $ingredient = Ingredient::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ingredient created successfully.',
            'data' => $ingredient
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $ingredient = Ingredient::find($id);
        
        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found.'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Ingredient retrieved successfully.',
            'data' => $ingredient
        ], 200);
    }

    public function update(UpdateIngredientRequest $request, int $id): JsonResponse
    {
        $ingredient = Ingredient::find($id);
        
        if (!$ingredient) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredient not found.'
            ], 404);
        }
        
        // Verificar autorización
        $this->authorize('update', $ingredient);
        
        $ingredient->update([
            'name' => $request->name,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ingredient updated successfully.',
            'data' => $ingredient
        ], 200);
    }

    public function destroy(Ingredient $ingredient): JsonResponse
    {
        $user = auth('api')->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }
        
        if ($ingredient->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete an ingredient that does not belong to you.'
            ], 403);
        }
        
        $ingredient->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Ingredient deleted successfully.'
        ], 200);
    }
}