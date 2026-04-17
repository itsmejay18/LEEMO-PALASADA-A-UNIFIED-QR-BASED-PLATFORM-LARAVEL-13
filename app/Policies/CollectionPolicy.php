<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;

class CollectionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager', 'Collector', 'Treasurer']);
    }

    public function view(User $user, Collection $collection): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager', 'Treasurer'])
            || ($user->hasRole('Collector') && $collection->collector_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin', 'Collector']);
    }

    public function update(User $user, Collection $collection): bool
    {
        if ($user->hasAnyRole(['Admin', 'Treasurer'])) {
            return true;
        }

        return $user->hasRole('Collector')
            && $collection->collector_id === $user->id
            && $collection->status !== 'verified';
    }

    public function delete(User $user, Collection $collection): bool
    {
        return $user->hasRole('Admin');
    }
}
