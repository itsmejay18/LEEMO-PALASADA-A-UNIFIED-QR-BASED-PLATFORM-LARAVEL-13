<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\MarketMap>
 */
class MarketMapFactory extends Factory
{
    public function definition(): array
    {
        $stall = fake()->unique()->regexify('[A-Z]-[0-9][0-9]');

        return [
            'stall_number' => $stall,
            'floor_level' => fake()->numberBetween(1, 2),
            'zone_section' => fake()->randomElement([
                'Fresh Produce',
                'Dry Goods',
                'Seafood Row',
                'Meat Alley',
                'Handicraft Hall',
                'Food Court',
            ]),
            'qr_location_code' => 'STALL-'.str_replace(' ', '-', $stall),
            'latitude' => fake()->randomFloat(8, 14.59500000, 14.60500000),
            'longitude' => fake()->randomFloat(8, 120.97900000, 120.98900000),
        ];
    }
}
