<?php

namespace Baytek\Laravel\Users\Roles;


use Baytek\Laravel\Users\User;

class Administrator extends Role
{
	const ROLE = 'Administrator';

	public $redirectTo = [
		'action' => 'Admin\Controller@getDashboard'
	];

	public function __construct(User $user)
	{
		parent::__construct($user);
	}
}
