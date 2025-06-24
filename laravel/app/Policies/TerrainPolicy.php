<?php

namespace App\Policies;

use App\Models\Terrain;
use App\Models\User;

class TerrainPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Terrain $terrain): bool
    {
        return true;
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
    public function update(User $user, Terrain $terrain): bool
    {
        return $user->id === $terrain->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Terrain $terrain): bool
    {
        return $user->id === $terrain->owner_id;
    }
}
