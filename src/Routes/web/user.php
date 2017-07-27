<?php

if(config('auth.skip_auth_routes') !== true) {
    Route::group([
        'prefix' => 'admin'
    ],
    function ($router) {
        \Auth::routes();
    });
}