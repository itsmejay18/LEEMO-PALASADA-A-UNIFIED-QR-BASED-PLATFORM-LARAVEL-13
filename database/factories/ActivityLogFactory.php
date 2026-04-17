<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement([
                'auth.login',
                'product.create',
                'collection.create',
                'collection.verify',
                'admin.role.update',
                'transaction.checkout',
            ]),
            'details' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
