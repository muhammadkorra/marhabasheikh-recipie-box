<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Resources\RecipeCollection;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index() {
        return new RecipeCollection(Recipe::with('ingredients')->get());
    }

    public function store(StoreRecipeRequest $request) {
        $recipe = Recipe::create($request->all());

        foreach($request->ingredients as $ingredient) {
            $recipe->ingredients()->attach($ingredient['id'], ['amount' => $ingredient['amount']]);
        }

        return response()->json(['message' => 'created'], 201);
    }
}
