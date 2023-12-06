<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     */

    public function test_should_index_all_ingredients(): void
    {
        $response = $this->get('/api/v1/ingredients');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'measure',
                        'supplier' => [
                            'id',
                            'name',
                            'email',
                            'phone'
                        ]
                    ]
                ]
            ]);
    }

    public function test_should_create_a_new_ingredient(): void {
        $payload = [
            'name' => $this->faker->unique()->randomElement(['Garlic', 'Mushrooms', 'Truffle', 'Corn', 'Onion', 'Ginger']),
            'measure' => 'pieces',
            'supplier' => 2
        ];

        $response = $this->json('POST', '/api/v1/ingredients', $payload, ['accept' => 'application/json']);
        $response->assertStatus(201)->assertJson(['data' => ['name' => $payload['name'], 'measure' => 'pieces']]);

        $this->assertDatabaseHas('ingredients', ['name' => $payload['name'], 'measure' => 'pieces', 'supplier_id' => 2]);
    }

    public function test_should_fail_to_create_if_ingredient_already_exists(): void {
        $ingredient = Ingredient::latest()->first();

        $payload = [
            'name' => $ingredient->name,
            'measure' => $ingredient->measure,
            'supplier' => $ingredient->supplier_id
        ];

        $response = $this->json('POST', '/api/v1/ingredients', $payload, ['accept' => 'application/json']);
        $response->assertStatus(422)->assertJson(['message' => 'The ingredient already exists.']);
    }

    public function test_should_fail_to_create_if_invalid_measure(): void {
        $payload = [
            'name' => 'Truffle Oil',
            'measure' => 'ounces', //invalid measure
            'supplier' => 3
        ];

        $response = $this->json('POST', '/api/v1/ingredients', $payload, ['accept' => 'application/json']);
        $response->assertStatus(422)->assertJson(['message' => 'The selected measure is invalid.']);
    }

    public function test_should_fail_to_create_if_invalid_supplier_id(): void {
        $supplier = Supplier::orderBy('id', 'desc')->first();

        $payload = [
            'name' => 'Sunflower Seeds',
            'measure' => 'g',
            'supplier' => $supplier->id += 1 //invalid supplier ID
        ];

        $response =$this->json('POST', '/api/v1/ingredients', $payload, ['accept' => 'application/json']);
        $response->assertStatus(422)->assertJson(['message' => 'The selected supplier is invalid.']);
    }
}
