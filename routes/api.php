<?php

use App\Http\Controllers\BoxController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\EstimateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1', 'middleware' => 'force.json'], function (){
    Route::apiResource('ingredients', IngredientController::class)->only(['index', 'store']);
    Route::apiResource('recipes', RecipeController::class)->only(['index', 'store']);
    Route::apiResource('boxes', BoxController::class)->only(['index', 'store']);
    Route::apiResource('estimates', EstimateController::class)->only(['index']);
});
