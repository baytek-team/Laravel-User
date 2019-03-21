<?php

/*
|--------------------------------------------------------------------------
| Avatar routes
|--------------------------------------------------------------------------
|
| For storing, deleting, and displaying member avatars
|
*/

// Store avatar
Route::post('/avatar/store/{avatar?}', 'AvatarController@store')->name('avatar.store');

// Update avatar
Route::put('/avatar/update/{avatar?}', 'AvatarController@update')->name('avatar.update');

// Delete avatar
Route::delete('/avatar/delete/{avatar?}', 'AvatarController@delete')->name('avatar.delete');

// Get a member's avatar
Route::get('/avatar/{avatar}', 'AvatarController@show')->name('avatar.show');

// Get members thumbnail
Route::get('/avatar/{avatar}/size/{size}', 'AvatarController@showThumbnail')->name('avatar.show.thumbnail');

/*
|--------------------------------------------------------------------------
| Member directory routes
|--------------------------------------------------------------------------
|
| For browsing the member directory
|
*/

// Gets a list of all members
Route::get('/', 'MemberController@all');

// Gets sorted lists of members by first name, last name, and the lists of letters
Route::get('/letters', 'MemberController@letters');

// Get members by first name letter
Route::get('/first/{letter}', 'MemberController@byFirstName');

// Get members by first name letter
Route::get('/last/{letter}', 'MemberController@byLastName');

/*
|--------------------------------------------------------------------------
| Current member routes
|--------------------------------------------------------------------------
|
| Viewing and managing the info related to the currently logged in member
|
*/

// Get the logged in member
Route::get('/profile', 'MemberController@profile');

// Reset password request from frontend of app
Route::post('/{member}/password/email', 'MemberController@sendPasswordResetLink')->name('api.members.password.email');

// Update a member's profile details
Route::post('/{member}/update', 'MemberController@update');

// Update a member's password
Route::post('/{member}/update/password', 'MemberController@updatePassword');

/*
|--------------------------------------------------------------------------
| Other member routes
|--------------------------------------------------------------------------
|
|
*/

// Get a specific member
Route::get('/{member}', 'MemberController@member');