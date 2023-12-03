<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredientRequest;
use App\Http\Resources\IngredientCollection;
use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use App\Services\FilterService;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Request $request){
        $filter = FilterService::makeFilter($request, ['name', 'id'], true);

        $ingredients = Ingredient::whereHas('supplier', function($query) use ($filter){
            // if no filter is set this is an empty array which does not affect the indexing
            $query->where($filter);
        })->with('supplier')->paginate();

        return new IngredientCollection($ingredients);
    }

    public function store(StoreIngredientRequest $request) {
        return new IngredientResource(Ingredient::create($request->all()));
    }
}
