<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pro_code' => 'PRO-' . $this->faker->unique()->numberBetween(10000, 99999),
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(10),
        ];
    }
}
