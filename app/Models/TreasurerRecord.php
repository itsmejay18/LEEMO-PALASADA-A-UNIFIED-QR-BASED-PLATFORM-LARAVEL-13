<?php

namespace App\Models;

use Database\Factories\TreasurerRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreasurerRecord extends Model
{
    /** @use HasFactory<TreasurerRecordFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'treasurer_id',
        'collection_id',
        'amount_verified',
        'verification_date',
        'official_receipt_number',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount_verified' => 'decimal:2',
            'verification_date' => 'datetime',
        ];
    }

    public function treasurer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'treasurer_id');
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
