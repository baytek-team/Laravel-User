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
        return $user->hasAnyRole(['Root', 'Administrator']);
    }
}
