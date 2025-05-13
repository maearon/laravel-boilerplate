<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Micropost;
use Illuminate\Auth\Access\HandlesAuthorization;

class MicropostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the micropost.
     */
    public function update(User $user, Micropost $micropost)
    {
        return $user->id === $micropost->user_id;
    }

    /**
     * Determine whether the user can delete the micropost.
     */
    public function delete(User $user, Micropost $micropost)
    {
        return $user->id === $micropost->user_id || $user->admin;
    }
}
