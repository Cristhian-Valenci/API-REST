<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cocktail;
use App\Http\Requests\Cocktail\CreateCocktailRequest;
use App\Http\Requests\Cocktail\UpdateCocktailRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class CocktailController extends Controller
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        $cocktails = Cocktail::with('ingredients')->paginate(16)->withQueryString();

        if ($cocktails->count() === 0) {
            return response()->json([
                'success' => true,
                'message' => 'No cocktails found.',
                'data' => []
            ], 200);
        }

        $cocktails->getCollection()->transform(function ($cocktail) {
            return $this->formatCocktail($cocktail);
        });

        return response()->json([
            'success' => true,
            'message' => 'Cocktails retrieved successfully.',
            'data' => $cocktails
        ], 200);
    }

    public function store(CreateCocktailRequest $request): JsonResponse
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
            'success' => true,
            'message' => 'Cocktail created successfully.',
            'data' => [
                'id' => $cocktail->id,
                'name' => $cocktail->name,
            ]
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $cocktail = Cocktail::with('ingredients')->find($id);

        if (!$cocktail) {
            return response()->json([
                'success' => false,
                'message' => 'Cocktail not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cocktail retrieved successfully.',
            'data' => $this->formatCocktail($cocktail)
        ], 200);
    }

    public function update(UpdateCocktailRequest $request, Cocktail $cocktail): JsonResponse
    {
        $this->authorize('update', $cocktail);

        $cocktail->update([
            'name' => ucfirst(strtolower($request->name)),
            'description' => $request->description,
            'elaboration_method' => $request->elaboration_method,
        ]);

        // Sync ingredients
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

        // Recargar el cocktail con sus ingredientes
        $cocktail->load('ingredients');

        return response()->json([
            'success' => true,
            'message' => 'Cocktail updated successfully.',
            'data' => $this->formatCocktail($cocktail)
        ], 200);
    }

    public function destroy(Cocktail $cocktail): JsonResponse
    {
        $this->authorize('delete', $cocktail);

        $cocktail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cocktail deleted successfully.'
        ], 200);
    }

    public function favorite(Cocktail $cocktail): JsonResponse
    {
        $user = auth()->user();

        if ($user->favoriteCocktails()->where('cocktail_id', $cocktail->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This cocktail is already in your favorites.'
            ], 409);
        }

        $user->favoriteCocktails()->attach($cocktail->id);

        return response()->json([
            'success' => true,
            'message' => 'Cocktail added to favorites successfully.'
        ], 200);
    }

    public function unfavorite(Cocktail $cocktail): JsonResponse
    {
        $user = auth()->user();

        if (!$user->favoriteCocktails()->where('cocktail_id', $cocktail->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This cocktail is not in your favorites.'
            ], 404);
        }

        $user->favoriteCocktails()->detach($cocktail->id);

        return response()->json([
            'success' => true,
            'message' => 'Cocktail removed from favorites successfully.'
        ], 200);
    }

    public function search(Request $request): JsonResponse
    {
        $query = Cocktail::query()->with('ingredients');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . strtolower($request->name) . '%');
        }

        if ($request->filled('ingredient')) {
            $query->whereHas('ingredients', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->ingredient . '%');
            });
        }

        if ($request->boolean('favorite')) {
            if (!auth('api')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be authenticated to filter by favorites.'
                ], 401);
            }

            $query->whereHas('favoritedBy', function ($q) {
                $q->where('users.id', auth('api')->id());
            });
        }

        if ($request->filled('order')) {
            try {
                $this->applyOrder(
                    $query,
                    $request->order,
                    $request->get('direction', 'asc')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 401);
            }
        }

        $cocktails = $query->paginate(16)->withQueryString();

        if ($cocktails->count() === 0) {
            return response()->json([
                'success' => true,
                'message' => 'No cocktails found matching your search criteria.',
                'data' => []
            ], 200);
        }

        $cocktails->getCollection()->transform(function ($cocktail) {
            return $this->formatCocktail($cocktail);
        });

        return response()->json([
            'success' => true,
            'message' => 'Cocktails retrieved successfully.',
            'data' => $cocktails
        ], 200);
    }

    private function applyOrder($query, $order, $direction = 'asc')
    {
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        switch ($order) {
            case 'name':
                $query->orderBy('name', $direction);
                break;
            case 'created_at':
                $query->orderBy('created_at', $direction)
                    ->orderBy('id', $direction);
                break;
            case 'favorites_first':
                if (!auth('api')->check()) {
                    throw new \Exception('You must be authenticated to sort by favorites.');
                }

                $query->withCount([
                    'favoritedBy as is_favorite' => function ($q) {
                        $q->where('users.id', auth('api')->id());
                    }
                ])->orderByDesc('is_favorite');
                break;
        }

        return $query;
    }

    private function formatCocktail($cocktail)
    {
        $ingredients = $cocktail->ingredients->map(function ($ingredient) {
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
    }
}