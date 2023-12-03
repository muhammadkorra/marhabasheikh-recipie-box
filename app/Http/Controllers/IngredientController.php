<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredientRequest;
use App\Http\Resources\IngredientCollection;
use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index(){
        return new IngredientCollection(Ingredient::with('supplier')->get());
    }

    public function store(StoreIngredientRequest $request) {
        return new IngredientResource(Ingredient::create($request->all()));
    }
}
