<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::role('Customer')->get();
        $vendors = Vendor::with('products')->get()->filter(fn (Vendor $vendor) => $vendor->products->isNotEmpty());

        if ($customers->isEmpty() || $vendors->isEmpty()) {
            return;
        }

        foreach (range(1, 30) as $index) {
            $customer = $customers->random();
            $vendor = $vendors->random();
            $products = $vendor->products->random(min(rand(1, 3), $vendor->products->count()));
            $products = $products instanceof Product ? collect([$products]) : collect($products);
            $paymentMethod = fake()->randomElement(['cash', 'qrph']);

            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'vendor_id' => $vendor->id,
                'total_amount' => 0,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cash' ? 'paid' : fake()->randomElement(['paid', 'pending']),
                'transaction_date' => fake()->dateTimeBetween('-40 days', 'now'),
            ]);

            $total = 0;

            foreach ($products as $product) {
                $quantity = rand(1, 3);
                $subtotal = $quantity * (float) $product->price;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $transaction->update(['total_amount' => $total]);
        }
    }
}
