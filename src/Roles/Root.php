<?php

namespace Baytek\Laravel\Users\Roles;

use Auth;
use Menu;
use Route;

use Baytek\Laravel\Users\User;

class Root extends Role
{
	const ROLE = 'Root';

	public $redirectTo = [
		'action' => '/'
	];

	public function __construct(User $user)
	{
		parent::__construct($user);
	}
}
