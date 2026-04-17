<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected string $guard_name = 'web';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'vendor_id',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customerTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class, 'collector_id');
    }

    public function treasurerRecords(): HasMany
    {
        return $this->hasMany(TreasurerRecord::class, 'treasurer_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function favoriteVendors(): HasMany
    {
        return $this->hasMany(FavoriteVendor::class);
    }

    public function bookmarkedVendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'favorite_vendors')->withTimestamps();
    }

    public function primaryRole(): ?string
    {
        return $this->getRoleNames()->first();
    }

    public function dashboardRoute(): string
    {
        return match (true) {
            $this->hasRole('Admin') => 'admin.dashboard',
            $this->hasRole('Manager') => 'manager.dashboard',
            $this->hasRole('Collector') => 'collector.dashboard',
            $this->hasRole('Treasurer') => 'treasurer.dashboard',
            $this->hasRole('Vendor') => 'vendor.dashboard',
            default => 'customer.dashboard',
        };
    }
}
