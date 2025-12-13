<?php

namespace App\Policies;

use App\Models\Installation;
use App\Models\User;

class InstallationPolicy
{
    public function view(User $user, Installation $installation): bool
    {
        return in_array($user->role->name, ['admin', 'technician', 'viewer']);
    }

    public function update(User $user, Installation $installation): bool
    {
        return in_array($user->role->name, ['admin', 'technician']);
    }
}

