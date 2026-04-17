<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Collection>
 */
class CollectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'collector_id' => User::factory(),
            'vendor_id' => Vendor::factory(),
            'amount_collected' => fake()->randomFloat(2, 200, 3000),
            'collection_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'proof_of_collection_path' => null,
            'status' => fake()->randomElement(['submitted', 'verified', 'rejected']),
            'remarks' => fake()->sentence(),
        ];
    }
}
