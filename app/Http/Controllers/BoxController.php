<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoxRequest;
use App\Http\Resources\BoxResource;
use App\Http\Resources\BoxCollection;
use App\Models\Box;

class BoxController extends Controller
{
    public function index(){
        return new BoxCollection(Box::with('recipes')->get());
    }

    public function store(StoreBoxRequest $request){
        $box = Box::create([
            'delivery_date' => $request->delivery_date
        ]);

        $box->recipes()->attach($request['recipe_ids']);
        $box->load('recipes');

        return new BoxResource($box);
    }
}
