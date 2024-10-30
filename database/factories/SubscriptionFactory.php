<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sub_code' => 'SUB-' . $this->faker->unique()->numberBetween(10000, 99999),
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
