<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager', 'Vendor']);
    }

    public function view(User $user, Product $product): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager'])
            || ($user->hasRole('Vendor') && $user->vendor_id === $product->vendor_id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Vendor') && filled($user->vendor_id);
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager'])
            || ($user->hasRole('Vendor') && $user->vendor_id === $product->vendor_id);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager'])
            || ($user->hasRole('Vendor') && $user->vendor_id === $product->vendor_id);
    }
}
