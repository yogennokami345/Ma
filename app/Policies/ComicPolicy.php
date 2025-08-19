<?php

namespace App\Policies;

use App\Models\Comic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComicPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('comic_view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comic $comic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_comic');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comic $comic): bool
    {
        return $user->hasPermissionTo('edit_comic');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comic $comic): bool
    {
        return $user->hasPermissionTo('delete_comic');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comic $comic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comic $comic): bool
    {
        return false;
    }
}
