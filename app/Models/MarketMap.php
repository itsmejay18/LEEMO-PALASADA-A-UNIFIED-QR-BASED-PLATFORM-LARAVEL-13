<?php

namespace App\Models;

use Database\Factories\MarketMapFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MarketMap extends Model
{
    /** @use HasFactory<MarketMapFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'stall_number',
        'floor_level',
        'zone_section',
        'qr_location_code',
        'latitude',
        'longitude',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class, 'stall_number', 'stall_number');
    }
}
