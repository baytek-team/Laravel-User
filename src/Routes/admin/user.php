<?php

/*
    User Routes for adding and managing users.
*/
Route::middleware('role', \Baytek\Laravel\Users\Middleware\RoleMiddleware::class);

Route::get('user/roles', 'UserRoleController@index')->name('user.role.index');
Route::post('user/roles/role-permissions', 'UserRoleController@saveRolePermissions')->name('user.role.save_role_permissions');
Route::post('user/roles/user-permissions', 'UserRoleController@saveUserPermissions')->name('user.role.save_user_permissions');
Route::post('user/roles/user-roles', 'UserRoleController@saveUserRoles')->name('user.role.save_user_roles');
Route::get('user/profile', 'ProfileController@index')->name('user.profile');

Route::resource('user', 'UserController');
Route::post('user/{user}/password/email', 'UserController@sendPasswordResetLink')->name('user.password.email');

Route::get('user/{user}/roles', 'UserController@roles')->name('user.roles');
Route::post('user/{user}/roles', 'UserRoleController@saveUserRoles')->name('user.roles.save');
Route::post('user/{user}/permissions', 'UserRoleController@saveUserPermissions')->name('user.permissions.save');

Route::resource('role', 'RoleController');
Route::resource('permission', 'PermissionController');

