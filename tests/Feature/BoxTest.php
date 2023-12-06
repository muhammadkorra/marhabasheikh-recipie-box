<?php

namespace Tests\Feature;

use App\Models\Box;
use App\Models\Recipe;
use Tests\TestCase;

class BoxTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_should_index_all_boxes(): void
    {
        $response = $this->get('/api/v1/boxes');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 
                    'delivery_date', 
                    'created_at', 
                    'updated_at', 
                    'recipes' => [
                        '*' => [
                            'id', 
                            'name', 
                            'description'
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function test_should_create_a_new_box(): void {
        $recipes = Recipe::select('id')->take(4)->get()->map(fn($item) => $item['id']);
        $box = Box::latest()->first();

        $payload = [
            'delivery_date' => now()->addDays(2)->toDateString(),
            'recipe_ids' => $recipes
        ];

        $response = $this->json('POST', '/api/v1/boxes', $payload, ['accept' => 'application/json']);
        $response->assertStatus(201);

        $this->assertDatabaseHas('boxes', ['id' => $box->id += 1, 'delivery_date' => $payload['delivery_date']]);
    }

    public function test_should_fail_if_delivery_date_is_sooner_than_48_hours(): void {
        $recipes = Recipe::select('id')->take(4)->get()->map(fn($item) => $item['id']);

        $payload = [
            'delivery_date' => now()->addDays(1)->toDateString(),
            'recipe_ids' => $recipes
        ];

        $response = $this->json('POST', '/api/v1/boxes', $payload, ['accept' => 'application/json']);
        $response
            ->assertStatus(422)
            ->json(['message' => 'The delivery date field must be a date after or equal to ' . now()->addDays(2)->toDateString()]);
    }

    public function test_should_fail_if_request_includes_more_than_4_recipes(): void {
        $recipes = Recipe::select('id')->take(5)->get()->map(fn($item) => $item['id']);

        $payload = [
            'delivery_date' => now()->addDays(2)->toDateString(),
            'recipe_ids' => $recipes
        ];

        $response = $this->json('POST', '/api/v1/boxes', $payload, ['accept' => 'application/json']);
        $response
            ->assertStatus(422)
            ->json(['message' => 'The recipe ids field must not have more than 4 items.']);
    }

    public function test_should_fail_if_recipe_id_does_not_exist(): void {
        $recipe = Recipe::latest()->first();

        $payload = [
            'delivery_date' => now()->addDays(2)->toDateString(),
            'recipe_ids' => [$recipe->id += 1]
        ];

        $response = $this->json('POST', '/api/v1/boxes', $payload, ['accept' => 'application/json']);
        $response
            ->assertStatus(422)
            ->json(['message' => 'The selected recipe_ids.0 is invalid.']);
    }
}
