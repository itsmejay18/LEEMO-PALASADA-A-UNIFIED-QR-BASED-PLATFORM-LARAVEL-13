<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    public function definition(): array
    {
        $key = Str::slug(fake()->unique()->words(2, true), '_');

        return [
            'key' => $key,
            'label' => Str::headline($key),
            'value' => fake()->sentence(),
            'group' => 'general',
        ];
    }
}
