<?php

namespace Baytek\Laravel\Users\Controllers;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\Requests\RoleRequest;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
	/**
	 * Constructing the Role Controller Class
	 */

	public function index()
	{
		return view('user::role.roles', [
			'roles' => Role::all(),
			'users' => User::all(),
			'permissions' => Permission::all(),
		]);
	}

	public function saveRolePermissions(RoleRequest $post)
	{
		Role::all()->each(function ($role) {
			$role->permissions()->detach();
		});

		foreach($post->request as $role => $permissions) {

			if(is_array($permissions)) {
				$roleModel = Role::findByName($role);

				$permissions = collect(array_keys($permissions))
					->flatten()
					->map(function ($permission) {
						return app(Permission::class)->findByName($permission);
					})
					->all();

				$roleModel->permissions()->saveMany($permissions);

				//Reset the permission cache, otherwise the changes won't be saved!
				$roleModel->forgetCachedPermissions();
			}
		}

		return redirect()->back();
	}

	public function saveUserRoles(RoleRequest $post, User $user = null)
	{
		if(is_null($user->id)) {
			User::all()->each(function ($user) {
				$user->roles()->detach();
			});
		}
		else {
			$user->roles()->detach();
		}

		foreach($post->request as $role => $users) {

			if(is_array($users)) {
				$roleModel = Role::findByName($role);

				collect(array_keys($users))->each(function ($user) use ($roleModel) {
					User::find($user)->assignRole($roleModel);
				});
			}
		}

		return redirect()->back();
	}

	public function saveUserPermissions(RoleRequest $post, User $user = null)
	{
		if(is_null($user->id)) {
			User::all()->each(function ($user) {
				$user->permissions()->detach();
			});
		}
		else {
			$user->permissions()->detach();
		}

		foreach($post->request as $permission => $users) {

			if(is_array($users)) {
				$permissionModel = Permission::findByName(str_replace('_', ' ', $permission));

				collect(array_keys($users))->each(function ($user) use ($permissionModel) {
					User::find($user)->givePermissionTo($permissionModel);
				});
			}
		}

		return redirect()->back();
	}

}
