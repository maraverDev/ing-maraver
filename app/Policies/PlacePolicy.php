<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Place;

class PlacePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('technician');
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('technician')
            || $user->hasRole('viewer');
    }
}
