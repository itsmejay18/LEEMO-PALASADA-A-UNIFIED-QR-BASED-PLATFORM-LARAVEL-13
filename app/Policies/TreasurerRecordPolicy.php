<?php

namespace App\Policies;

use App\Models\TreasurerRecord;
use App\Models\User;

class TreasurerRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager', 'Treasurer']);
    }

    public function view(User $user, TreasurerRecord $treasurerRecord): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager'])
            || ($user->hasRole('Treasurer') && $treasurerRecord->treasurer_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Treasurer']);
    }
}
