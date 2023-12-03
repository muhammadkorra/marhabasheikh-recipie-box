<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validDate = Carbon::parse($request->query('order_date'));
        
        $boxes = Box::whereBetween('delivery_date', [$validDate->toDateString(), $validDate->addDays(7)->toDateString()])
            ->leftJoin('box_recipe', 'boxes.id', '=', 'box_recipe.box_id')
            ->leftJoin('recipes', 'box_recipe.recipe_id', '=', 'recipes.id')
            ->leftJoin('ingredient_recipe', 'ingredient_recipe.recipe_id', '=', 'recipes.id')
            ->leftJoin('ingredients', 'ingredients.id', '=', 'ingredient_recipe.ingredient_id')
            ->selectRaw('ingredients.id, ingredients.name, ingredients.measure, sum(ingredient_recipe.amount) as total_amount')
            ->groupBy('ingredients.id')
            ->get();

        return response()->json($boxes);
    }
}
