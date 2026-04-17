<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TreasurerRecord>
 */
class TreasurerRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'treasurer_id' => User::factory(),
            'collection_id' => Collection::factory(),
            'amount_verified' => fake()->randomFloat(2, 200, 3000),
            'verification_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'official_receipt_number' => 'OR-'.fake()->unique()->numerify('######'),
            'notes' => fake()->sentence(),
        ];
    }
}
