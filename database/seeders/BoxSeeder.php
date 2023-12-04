<?php

namespace Database\Seeders;

use App\Models\Box;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = Recipe::all();
        Box::factory(6)->create()->each(function($box) use ($recipes){
            $box->recipes()->saveMany($recipes);
        });
    }
}
