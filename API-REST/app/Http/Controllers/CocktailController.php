<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cocktail;
use App\Http\Requests\Cocktail\CreateCocktailRequest;
use App\Http\Requests\Cocktail\UpdateCocktailRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CocktailController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        $cocktails = Cocktail::with('ingredients')->get();

        if ($cocktails->isEmpty()) {
            return response()->json([], 204);
        }

        $cocktailsArray = $cocktails->map(function($cocktail) {
            $ingredients = $cocktail->ingredients->map(function($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'amount' => $ingredient->pivot->amount,
                    'unit' => $ingredient->pivot->unit,
                ];
            });
            
            return [
                'id' => $cocktail->id,
                'name' => $cocktail->name,
                'description' => $cocktail->description,
                'elaboration_method' => $cocktail->elaboration_method,
                'user_id' => $cocktail->user_id,
                'created_at' => $cocktail->created_at,
                'updated_at' => $cocktail->updated_at,
                'ingredients' => $ingredients,
            ];
        });

        return response()->json($cocktailsArray, 200);
    }



    public function store(CreateCocktailRequest $request)
    {

        $cocktail = Cocktail::create([
            'name' => ucfirst(strtolower($request->name)),
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

    public function show(int $id)
    {
        $cocktail = Cocktail::with('ingredients')->find($id);

        if (!$cocktail) {
            return response()->json([
                'message' => 'Cocktail not found',
            ], 404);
        }

        $ingredients = $cocktail->ingredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'amount' => $ingredient->pivot->amount,
                'unit' => $ingredient->pivot->unit,
            ];
        });

        return response()->json([
            'id' => $cocktail->id,
            'name' => $cocktail->name,
            'description' => $cocktail->description,
            'elaboration_method' => $cocktail->elaboration_method,
            'user_id' => $cocktail->user_id,
            'created_at' => $cocktail->created_at,
            'updated_at' => $cocktail->updated_at,
            'ingredients' => $ingredients,
        ], 200);
    }



    public function update(UpdateCocktailRequest $request, Cocktail $cocktail)
    {
        $this->authorize('update', $cocktail);

        $cocktail->update([
            'name' => ucfirst(strtolower($request->name)),
            'description' => $request->description,
            'elaboration_method' => $request->elaboration_method,
        ]);

        
        $cocktail->ingredients()->sync([]);

        foreach ($request->ingredients as $ingredient) {
            $cocktail->ingredients()->attach(
                $ingredient['ingredient_id'],
                [
                    'amount' => $ingredient['amount'],
                    'unit' => $ingredient['unit'],
                ]
            );
        }

        return response()->json($cocktail, 200);
    }

    public function destroy(Cocktail $cocktail)
    {
        $this->authorize('delete', $cocktail);

        $cocktail->delete();

        return response()->json(null, 204);
    }





}
