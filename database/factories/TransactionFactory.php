<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $paymentMethod = fake()->randomElement(['cash', 'qrph']);

        return [
            'customer_id' => User::factory(),
            'vendor_id' => Vendor::factory(),
            'total_amount' => fake()->randomFloat(2, 100, 1500),
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentMethod === 'cash' ? 'paid' : fake()->randomElement(['paid', 'pending']),
            'transaction_date' => fake()->dateTimeBetween('-45 days', 'now'),
        ];
    }
}
