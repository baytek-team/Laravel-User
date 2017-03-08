<?php

namespace Baytek\Laravel\Users\Policies;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\Roles\Administrator;
use Baytek\Laravel\Users\Roles\Root;

use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
     * @param  Spatie\Permission\Models\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role = null)
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
     * @param  Spatie\Permission\Models\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return $user->can('Update Role');
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Spatie\Permission\Models\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return $user->can('Delete Role');
    }

}
