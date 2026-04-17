<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'product_name' => fake()->words(3, true),
            'description' => fake()->sentence(12),
            'price' => fake()->randomFloat(2, 20, 500),
            'stock_quantity' => fake()->numberBetween(10, 120),
            'qr_code_path' => null,
            'is_available' => fake()->boolean(90),
        ];
    }
}
