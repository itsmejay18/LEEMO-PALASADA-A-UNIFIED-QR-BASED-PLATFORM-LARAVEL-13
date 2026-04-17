<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager', 'Vendor']);
    }

    public function view(User $user, Vendor $vendor): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager'])
            || $user->vendor_id === $vendor->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager']);
    }

    public function update(User $user, Vendor $vendor): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager']);
    }

    public function delete(User $user, Vendor $vendor): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager']);
    }
}
