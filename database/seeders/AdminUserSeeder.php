<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@leemo.com'],
            [
                'name' => 'LEEMO Admin',
                'password' => 'password',
                'vendor_id' => null,
            ],
        );

        $admin->syncRoles(['Admin']);
    }
}
