<?php

namespace Baytek\Laravel\Users\Roles;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\Roles\Role;

class Member extends Role
{
	const ROLE = 'Member';

	public $redirectTo = [
		'url' => '/app'
	];

	public function __construct(User $user)
	{
		parent::__construct($user);
	}

	/**
     * Get all users of the role type
     * @return Collection Users
     */
    public static function users()
    {
        return \Baytek\Laravel\Users\Member::whereHas('roles', function ($query) {
            $query->where('roles.name', '=', static::ROLE);
        });
    }
}