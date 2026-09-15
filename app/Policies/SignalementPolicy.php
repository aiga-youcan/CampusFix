<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Signalement;

class SignalementPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Signalement $signalement): bool
    {
        return $user->hasRole(['admin', 'technicien']) || $user->id === $signalement->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Signalement $signalement): bool
    {
        return $user->hasRole('admin') || ($user->id === $signalement->user_id && $signalement->status === 'signale');
    }

    public function delete(User $user, Signalement $signalement): bool
    {
        return $user->hasRole('admin');
    }

    public function intervene(User $user, Signalement $signalement): bool
    {
        return $user->hasRole(['admin', 'technicien']);
    }
}
