<?php

namespace Database\Seeders;

use App\Models\MarketMap;
use Illuminate\Database\Seeder;

class MarketMapSeeder extends Seeder
{
    public function run(): void
    {
        $baseLat = 14.59951200;
        $baseLng = 120.98422200;
        $zones = [
            'A' => 'Fresh Produce',
            'B' => 'Meat Alley',
            'C' => 'Seafood Row',
            'D' => 'Dry Goods',
            'E' => 'Handicraft Hall',
            'F' => 'Food Court',
        ];

        $rowIndex = 0;

        foreach (range('A', 'F') as $row) {
            foreach (range(1, 6) as $column) {
                $stallNumber = sprintf('%s-%02d', $row, $column);

                MarketMap::updateOrCreate(
                    ['stall_number' => $stallNumber],
                    [
                        'floor_level' => $rowIndex < 3 ? 1 : 2,
                        'zone_section' => $zones[$row],
                        'qr_location_code' => 'STALL-'.$stallNumber,
                        'latitude' => $baseLat + ($rowIndex * 0.00018),
                        'longitude' => $baseLng + (($column - 1) * 0.00018),
                    ],
                );
            }

            $rowIndex++;
        }
    }
}
