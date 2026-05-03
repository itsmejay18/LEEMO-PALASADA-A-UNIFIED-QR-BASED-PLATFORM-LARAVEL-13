<?php

namespace Database\Seeders;

use App\Models\FavoriteVendor;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::updateOrCreate(
            ['email' => 'manager@leemo.com'],
            ['name' => 'Market Manager', 'password' => 'password', 'vendor_id' => null],
        );
        $manager->syncRoles(['Manager']);

        foreach ([
            ['email' => 'collector1@leemo.com', 'name' => 'Collector One'],
            ['email' => 'collector2@leemo.com', 'name' => 'Collector Two'],
        ] as $collectorData) {
            $collector = User::updateOrCreate(
                ['email' => $collectorData['email']],
                ['name' => $collectorData['name'], 'password' => 'password', 'vendor_id' => null],
            );
            $collector->syncRoles(['Collector']);
        }

        $treasurer = User::updateOrCreate(
            ['email' => 'treasurer@leemo.com'],
            ['name' => 'Market Treasurer', 'password' => 'password', 'vendor_id' => null],
        );
        $treasurer->syncRoles(['Treasurer']);

        Vendor::query()->orderBy('id')->get()->each(function (Vendor $vendor, int $index): void {
            $user = User::updateOrCreate(
                ['email' => 'vendor'.($index + 1).'@leemo.com'],
                [
                    'name' => $vendor->vendor_name.' Owner',
                    'password' => 'password',
                    'vendor_id' => $vendor->id,
                ],
            );

            $user->syncRoles(['Vendor']);
        });

        for ($i = 1; $i <= 12; $i++) {
            $customer = User::updateOrCreate(
                ['email' => 'customer@leemo.com'],
                [
                    'name' => 'Customer '.$i,
                    'password' => 'password',
                    'vendor_id' => null,
                ],
            );

            $customer->syncRoles(['Customer']);
        }

        $vendors = Vendor::query()->pluck('id')->all();

        User::role('Customer')->get()->each(function (User $customer) use ($vendors): void {
            $favoriteVendorIds = collect($vendors)->shuffle()->take(3);

            foreach ($favoriteVendorIds as $vendorId) {
                FavoriteVendor::firstOrCreate([
                    'user_id' => $customer->id,
                    'vendor_id' => $vendorId,
                ]);
            }
        });
    }
}
