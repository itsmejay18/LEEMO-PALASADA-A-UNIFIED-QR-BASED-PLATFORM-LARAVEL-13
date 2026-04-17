<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    public function definition(): array
    {
        $stall = fake()->unique()->regexify('[A-Z]-[0-9][0-9]');

        return [
            'vendor_name' => fake()->company().' Market',
            'stall_number' => $stall,
            'contact_number' => fake()->numerify('09#########'),
            'email' => fake()->unique()->safeEmail(),
            'logo_path' => null,
            'is_active' => true,
        ];
    }
}
