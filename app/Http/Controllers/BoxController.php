<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoxRequest;
use App\Http\Resources\BoxResource;
use App\Http\Resources\BoxCollection;
use App\Models\Box;
use Illuminate\Http\Request;
use App\Services\FilterService;

class BoxController extends Controller
{
    public function index(Request $request){
        // filters by delivery_date if it is set correctly in query
        $filter = FilterService::makeFilter($request, ['delivery_date']);

        return new BoxCollection(Box::where($filter)->with('recipes')->paginate());
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
