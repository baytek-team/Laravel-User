<?php

/*
	User Routes for adding and managing users.
*/

// use Auth;

Route::middleware('role', \Baytek\Laravel\Users\Middleware\RoleMiddleware::class);

Route::group([
    'middleware' => ['web'],
    'namespace' => \Baytek\Laravel\Users\Controllers::class,
    'prefix' => 'admin'
],
function ($router) {
    \Auth::routes();
});

Route::group([
		'namespace' => '\Baytek\Laravel\Users\Controllers',
		'prefix' => 'admin',
		'middleware' => ['web']
	], function () {

	Route::get('user/profile', 'ProfileController@index')->name('user.profile');

	Route::resource('user', 'UserController');

	Route::get('user/{user}/roles', 'UserController@roles')->name('user.roles');
	Route::post('user/{user}/roles', 'UserController@rolesSave')->name('user.roles.save');

	Route::get('roles', 'RoleController@index')->name('roles.index');
    Route::post('roles/role-permissions', 'RoleController@saveRolePermissions')->name('user.role.save_role_permissions');
    Route::post('roles/user-permissions', 'RoleController@saveUserPermissions')->name('user.role.save_user_permissions');
    Route::post('roles/user-roles', 'RoleController@saveUserRoles')->name('user.role.save_user_roles');
});
