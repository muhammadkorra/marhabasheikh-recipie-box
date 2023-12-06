<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_index_all_recipes(): void
    {
        $response = $this->get('/api/v1/recipes');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'ingredients' => [
                        '*' => ['id', 'name', 'measure', 'amount']
                    ]
                ]
            ]
        ]);
    }

    public function test_create_new_recipe(): void {
        $payload = [
            'name' => $this->faker->unique()->randomElement(['Quinoa Roasted Tomatoes', 'Egg Benedict', 'Shakshouka', 'Lamb chops']),
            'description' => 'The perfectly balanced meal for every day',
            'ingredients' => [['id' => 1, 'amount' => 150], ['id' => 2, 'amount' => 230]]
        ];

        $response = $this->json('POST', '/api/v1/recipes', $payload, ['accept' => 'application/json']);
        $response->assertStatus(201)->json(['message' => 'created']);

        $this->assertDatabaseHas('recipes', ['name' => $payload['name'], 'description' => $payload['description']]);
    }

    public function test_fails_to_create_if_ingredient_measure_is_invalid(): void {
        $ingredient = Ingredient::where('measure', '=', 'pieces')->first();

        $payload = [
            'name' => 'Chicken Alfredo',
            'description' => 'Italian handmade pasta with Alfredo sauce and pieces of grilled chicken',
            'ingredients' => [
                ['id' => $ingredient->id, 'amount' => 2.5]
            ]
        ];

        $response = $this->json('POST', '/api/v1/recipes', $payload, ['accept' => 'application/json']);

        $response->assertStatus(422)->json(['message' => 'The amount cannot contain fractions when the ingredient measure is "pieces"']);
    }

    public function test_fails_to_create_if_ingredient_id_is_invalid(): void {
        $ingredient = Ingredient::latest()->first();

        $payload = [
            'name' => 'Chicken Alfredo',
            'description' => 'Italian handmade pasta with Alfredo sauce and pieces of grilled chicken',
            'ingredients' => [
                ['id' => $ingredient->id += 1, 'amount' => 2]
            ]
        ];

        $response = $this->json('POST', '/api/v1/recipes', $payload, ['accept' => 'application/json']);

        $response->assertStatus(422)->json(['message' => 'The selected ingredients.0.id is invalid']);
    }
}
