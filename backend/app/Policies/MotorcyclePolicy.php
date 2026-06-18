<?php

namespace App\Policies;

use App\Models\Motorcycle;
use App\Models\User;

class MotorcyclePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Motorcycle $motorcycle): bool
    {
        return $user->id === $motorcycle->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Motorcycle $motorcycle): bool
    {
        return $user->id === $motorcycle->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Motorcycle $motorcycle): bool
    {
        return $user->id === $motorcycle->user_id;
    }
}
