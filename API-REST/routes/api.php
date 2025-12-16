<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;



Route::post('/users', [UserController::class, 'store'] );
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::patch('/users/{id}', [UserController::class, 'partial']); 
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::get('/ingredients', [IngredientController::class, 'index']);
Route::get('/ingredients/{id}', [IngredientController::class, 'show']);
Route::middleware('auth:api')->group(function () {
    Route::put('/ingredients/{ingredient}', [IngredientController::class, 'update']);
    Route::post('/ingredients', [IngredientController::class, 'store']);
});

Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy']);


