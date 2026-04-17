<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'market_name',
                'label' => 'Market Name',
                'value' => 'LEEMO-PALASADA Public Market',
                'group' => 'branding',
            ],
            [
                'key' => 'support_email',
                'label' => 'Support Email',
                'value' => 'support@leemo.com',
                'group' => 'contact',
            ],
            [
                'key' => 'announcement',
                'label' => 'Announcement',
                'value' => 'Scan QR markers for faster shopping, smarter collections, and easier stall navigation.',
                'group' => 'general',
            ],
            [
                'key' => 'featured_zone',
                'label' => 'Featured Zone',
                'value' => 'Fresh Produce',
                'group' => 'marketing',
            ],
            [
                'key' => 'enable_qrph',
                'label' => 'Enable QRPh Simulation',
                'value' => '1',
                'group' => 'payments',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting,
            );
        }
    }
}
