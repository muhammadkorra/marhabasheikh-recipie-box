<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Broccoli', 'Banana', 'Rice', 'Beef', 'Chicken', 'Prawns', 'Tomato', 'Potato']),
            'measure' => $this->faker->randomElement(['kg', 'g', 'pieces']),
            'supplier_id' => Supplier::factory()
        ];
    }
}
