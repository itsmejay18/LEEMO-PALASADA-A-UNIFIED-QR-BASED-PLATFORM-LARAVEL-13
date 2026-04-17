<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager', 'Vendor', 'Customer']);
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager'])
            || ($user->hasRole('Customer') && $transaction->customer_id === $user->id)
            || ($user->hasRole('Vendor') && $transaction->vendor_id === $user->vendor_id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Customer');
    }
}
