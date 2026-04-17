<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Vendor;
use App\Services\QrCodeService;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'Green Basket Produce' => ['Organic Lettuce Pack', 'Sweet Carrot Bundle', 'Spring Onion Mix', 'Fresh Tomato Basket', 'Premium Cucumber Tray'],
            'Sariwang Dagat Seafood' => ['Milkfish Fillet', 'Fresh Squid Pack', 'Sea Prawns', 'Blue Crab Selection', 'Mussels Family Pack'],
            'Bukid Harvest Goods' => ['Native Banana Cluster', 'Golden Corn Cobs', 'Sweet Potato Crate', 'Fresh Ginger Roots', 'Farm Garlic Set'],
            'Karne Central' => ['Pork Liempo Slice', 'Beef Sirloin Cut', 'Ground Pork Pack', 'Beef Short Ribs', 'Soup Bones Bundle'],
            'Lutong Bahay Corner' => ['Chicken Adobo Tray', 'Pork Menudo Box', 'Vegetable Kare-Kare', 'Laing Family Pack', 'Dinuguan Pot Meal'],
            'Palengke Poultry House' => ['Whole Dressed Chicken', 'Chicken Breast Pack', 'Chicken Wings Tray', 'Marinated Drumsticks', 'Chicken Liver Set'],
            'Farm Fresh Eggs' => ['Brown Eggs Dozen', 'Salted Egg Pack', 'Duck Eggs Basket', 'Jumbo White Eggs', 'Breakfast Egg Crate'],
            'Spice Route Pantry' => ['Turmeric Powder Jar', 'Black Pepper Refill', 'Rock Salt Pouch', 'Bay Leaf Pack', 'Annatto Seed Sachet'],
            'Handicraft Haven' => ['Woven Market Bag', 'Bamboo Serving Tray', 'Handmade Coaster Set', 'Abaca Storage Basket', 'Palm Fiber Placemat'],
            'Tropical Fruits Depot' => ['Ripe Mango Box', 'Pineapple Duo', 'Watermelon Slice Pack', 'Papaya Harvest Set', 'Calamansi Bundle'],
            'Rice and Grain Hub' => ['Premium Jasmine Rice', 'Brown Rice Sack', 'Glutinous Rice Bag', 'Mung Bean Pack', 'White Corn Grits'],
            'Street Snack Junction' => ['Kikiam Snack Pack', 'Fish Ball Combo', 'Banana Cue Set', 'Turon Box', 'Puto Cheese Tray'],
        ];

        $qrCodeService = app(QrCodeService::class);

        Vendor::query()->orderBy('vendor_name')->get()->each(function (Vendor $vendor) use ($catalog, $qrCodeService): void {
            $products = $catalog[$vendor->vendor_name] ?? collect(range(1, 5))->map(fn (int $number) => $vendor->vendor_name.' Product '.$number)->all();

            foreach ($products as $index => $productName) {
                $product = Product::updateOrCreate(
                    [
                        'vendor_id' => $vendor->id,
                        'product_name' => $productName,
                    ],
                    [
                        'description' => 'Freshly prepared and market-ready item from '.$vendor->vendor_name.'.',
                        'price' => 35 + ($index * 18) + ($vendor->id * 4),
                        'stock_quantity' => 20 + ($index * 5) + $vendor->id,
                        'is_available' => true,
                    ],
                );

                $qrCodeService->generateProductQr($product);
            }
        });
    }
}
