<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CocktailController extends Controller
{
    public function store(Request $request)
    {
        return response()->json([], 201);
    }
}
