<?php

namespace Baytek\Laravel\Users\Policies;

use Baytek\Laravel\Users\User;

use Illuminate\Auth\Access\HandlesAuthorization;

use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user is admin or root.
     * If so they can do all the things.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @return mixed
     */
    public function before(User $user)
    {
        return $user->hasRole('Root') ?: null;
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Spatie\Permission\Models\Permission  $permission
     * @return mixed
     */
    public function view(User $user, Permission $permission = null)
    {
        return $user->can('View Role');
    }

    /**
     * Determine whether the user can create contents.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return $user->can('Create Role');
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Spatie\Permission\Models\Permission  $permission
     * @return mixed
     */
    public function update(User $user, Permission $permission)
    {
        return $user->can('Update Role');
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Spatie\Permission\Models\Permission  $permission
     * @return mixed
     */
    public function delete(User $user, Permission $permission)
    {
        return $user->can('Delete Role');
    }
}
