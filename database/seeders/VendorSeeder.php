<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            ['vendor_name' => 'Green Basket Produce', 'stall_number' => 'A-01', 'contact_number' => '09171230001', 'email' => 'greenbasket@leemo.com', 'category' => 'Produce'],
            ['vendor_name' => 'Sariwang Dagat Seafood', 'stall_number' => 'A-02', 'contact_number' => '09171230002', 'email' => 'saridagat@leemo.com', 'category' => 'Seafood'],
            ['vendor_name' => 'Bukid Harvest Goods', 'stall_number' => 'A-03', 'contact_number' => '09171230003', 'email' => 'bukidharvest@leemo.com', 'category' => 'Produce'],
            ['vendor_name' => 'Karne Central', 'stall_number' => 'B-01', 'contact_number' => '09171230004', 'email' => 'karnecentral@leemo.com', 'category' => 'Meat'],
            ['vendor_name' => 'Lutong Bahay Corner', 'stall_number' => 'B-02', 'contact_number' => '09171230005', 'email' => 'lutongbahay@leemo.com', 'category' => 'Food'],
            ['vendor_name' => 'Palengke Poultry House', 'stall_number' => 'C-01', 'contact_number' => '09171230006', 'email' => 'poultryhouse@leemo.com', 'category' => 'Poultry'],
            ['vendor_name' => 'Farm Fresh Eggs', 'stall_number' => 'C-02', 'contact_number' => '09171230007', 'email' => 'farmfresheggs@leemo.com', 'category' => 'Poultry'],
            ['vendor_name' => 'Spice Route Pantry', 'stall_number' => 'D-01', 'contact_number' => '09171230008', 'email' => 'spiceroute@leemo.com', 'category' => 'Dry Goods'],
            ['vendor_name' => 'Handicraft Haven', 'stall_number' => 'D-02', 'contact_number' => '09171230009', 'email' => 'handicrafthaven@leemo.com', 'category' => 'Handicraft'],
            ['vendor_name' => 'Tropical Fruits Depot', 'stall_number' => 'E-01', 'contact_number' => '09171230010', 'email' => 'tropicalfruits@leemo.com', 'category' => 'Produce'],
            ['vendor_name' => 'Rice and Grain Hub', 'stall_number' => 'F-01', 'contact_number' => '09171230011', 'email' => 'ricegrainhub@leemo.com', 'category' => 'Dry Goods'],
            ['vendor_name' => 'Street Snack Junction', 'stall_number' => 'F-02', 'contact_number' => '09171230012', 'email' => 'streetsnacks@leemo.com', 'category' => 'Food'],
        ];

        foreach ($vendors as $vendor) {
            Vendor::updateOrCreate(
                ['email' => $vendor['email']],
                array_merge($vendor, [
                    'contract_start_date' => now()->subMonths(6)->toDateString(),
                    'contract_end_date' => now()->addMonths(6)->toDateString(),
                    'monthly_rent' => 2500,
                    'is_active' => true,
                ]),
            );
        }
    }
}
