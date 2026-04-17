<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\TreasurerRecord;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collectors = User::role('Collector')->get();
        $treasurer = User::role('Treasurer')->first();
        $vendors = Vendor::query()->get();

        if ($collectors->isEmpty() || ! $treasurer) {
            return;
        }

        foreach ($vendors as $index => $vendor) {
            $status = match (true) {
                $index < 7 => 'verified',
                $index < 10 => 'submitted',
                default => 'rejected',
            };

            $amount = 500 + ($index * 125);

            $collection = Collection::create([
                'collector_id' => $collectors->random()->id,
                'vendor_id' => $vendor->id,
                'amount_collected' => $amount,
                'collection_date' => now()->subDays(rand(1, 20)),
                'proof_of_collection_path' => null,
                'status' => $status,
                'remarks' => $status === 'rejected'
                    ? 'Awaiting corrected remittance details.'
                    : 'Scheduled market collection completed.',
            ]);

            if ($status === 'verified') {
                TreasurerRecord::create([
                    'treasurer_id' => $treasurer->id,
                    'collection_id' => $collection->id,
                    'amount_verified' => $amount,
                    'verification_date' => now()->subDays(rand(0, 10)),
                    'official_receipt_number' => 'OR-2026-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'notes' => 'Collection verified and ready for treasury reporting.',
                ]);
            }
        }
    }
}
