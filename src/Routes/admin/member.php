<?php

Route::get('members/pending', 'AdminController@pending')->name('members.pending');
Route::get('members/deleted', 'AdminController@deleted')->name('members.deleted');
Route::get('members/administrators', 'AdminController@administrators')->name('members.adminindex');

Route::post('members/{user}/approve', 'AdminController@approve')->name('members.approve');
Route::post('members/{user}/decline', 'AdminController@decline')->name('members.decline');
Route::post('members/{user}/password/email', 'AdminController@sendPasswordResetLink')->name('member.password.email');

Route::resource('members', AdminController::class);