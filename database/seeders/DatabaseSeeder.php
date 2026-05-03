<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SettingSeeder::class,
            VendorSeeder::class,
            MarketMapSeeder::class,
            AdminUserSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
            CollectionSeeder::class,
            HeaderItemSeeder::class,
        ]);

        $users = User::query()->get();

        if ($users->isNotEmpty()) {
            ActivityLog::factory()->count(20)->make()->each(function (ActivityLog $log) use ($users): void {
                $log->user_id = $users->random()->id;
                $log->save();
            });
        }
    }
}
