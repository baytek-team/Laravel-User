<?php

namespace Baytek\Laravel\Users\Roles;

use Baytek\Laravel\Users\User;

use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles;

class Role
{
    private $user;

    /**
     * Role Constructor
     * @param Baytek\Laravel\Users\User $user User model used to attach specific role stuff
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get all users of the role type
     * @return Collection Users
     */
    public static function all()
    {
        return User::whereHas('roles', function ($query) {
                $query->where('roles.name', '=', static::ROLE);
            })
            ->get();
    }

    public static function role()
    {
        return SpatieRole::findByName(static::ROLE);
    }
}
