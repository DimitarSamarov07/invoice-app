<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Use FakerPHP to generate fake, but realistic data
        return [
            "description" => fake()->sentence(),
            "quantity" => fake()->numberBetween(1, 10),
            "unit_price" => fake()->randomFloat(2, 1,500),
        ];
    }
}
