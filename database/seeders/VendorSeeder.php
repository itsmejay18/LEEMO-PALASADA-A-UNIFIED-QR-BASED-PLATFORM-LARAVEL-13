<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            ['vendor_name' => 'Green Basket Produce', 'stall_number' => 'A-01', 'contact_number' => '09171230001', 'email' => 'greenbasket@leemo.com'],
            ['vendor_name' => 'Sariwang Dagat Seafood', 'stall_number' => 'A-02', 'contact_number' => '09171230002', 'email' => 'saridagat@leemo.com'],
            ['vendor_name' => 'Bukid Harvest Goods', 'stall_number' => 'A-03', 'contact_number' => '09171230003', 'email' => 'bukidharvest@leemo.com'],
            ['vendor_name' => 'Karne Central', 'stall_number' => 'B-01', 'contact_number' => '09171230004', 'email' => 'karnecentral@leemo.com'],
            ['vendor_name' => 'Lutong Bahay Corner', 'stall_number' => 'B-02', 'contact_number' => '09171230005', 'email' => 'lutongbahay@leemo.com'],
            ['vendor_name' => 'Palengke Poultry House', 'stall_number' => 'C-01', 'contact_number' => '09171230006', 'email' => 'poultryhouse@leemo.com'],
            ['vendor_name' => 'Farm Fresh Eggs', 'stall_number' => 'C-02', 'contact_number' => '09171230007', 'email' => 'farmfresheggs@leemo.com'],
            ['vendor_name' => 'Spice Route Pantry', 'stall_number' => 'D-01', 'contact_number' => '09171230008', 'email' => 'spiceroute@leemo.com'],
            ['vendor_name' => 'Handicraft Haven', 'stall_number' => 'D-02', 'contact_number' => '09171230009', 'email' => 'handicrafthaven@leemo.com'],
            ['vendor_name' => 'Tropical Fruits Depot', 'stall_number' => 'E-01', 'contact_number' => '09171230010', 'email' => 'tropicalfruits@leemo.com'],
            ['vendor_name' => 'Rice and Grain Hub', 'stall_number' => 'F-01', 'contact_number' => '09171230011', 'email' => 'ricegrainhub@leemo.com'],
            ['vendor_name' => 'Street Snack Junction', 'stall_number' => 'F-02', 'contact_number' => '09171230012', 'email' => 'streetsnacks@leemo.com'],
        ];

        foreach ($vendors as $vendor) {
            Vendor::updateOrCreate(
                ['email' => $vendor['email']],
                array_merge($vendor, ['is_active' => true]),
            );
        }
    }
}
