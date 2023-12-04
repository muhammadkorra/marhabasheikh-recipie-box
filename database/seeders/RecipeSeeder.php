<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get the available ingredients
        $ingredients = Ingredient::all();

        Recipe::factory()->count(4)->state(new Sequence(
            [
                'name' => 'Angus Steak with Mashed Potates', 
                'description' => 'A Savory meal for the Steak lovers. Premium Angus steak cooked to perfection with a side of mashed potatoes'
            ],
            [
                'name' => 'Seafood Lovers', 
                'description' => 'A taste of the sea. Take yourself away with your senses after having this meal with grilled fish with lemon and oil, with a side of scallops'
            ],
            [
                'name' => 'Grilled Chicken with Rice',
                'description' => 'Nothing matches the old classic'
            ],
            [
                'name' => 'Vegeterian',
                'description' => 'For our animal lovers, we love you back! Here is a balanced meal that will help you get through your day'
            ]
        ))->create()
        ->each(function($recipe) use ($ingredients){
            $seedingData = [];
            foreach($ingredients as $ingredient){
                $seedingData[$ingredient->id] = ['amount' => rand(4, 250)];
            }
            $recipe->ingredients()->attach($seedingData);
        });

    }
}
