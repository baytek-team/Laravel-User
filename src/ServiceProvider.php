<?php

namespace Baytek\Laravel\Users;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

use Auth;
use Route;

class ServiceProvider extends AuthServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->loadRoutesFrom(__DIR__.'/Routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../resources/Migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/Views', 'User');

        Route::group(['middleware' => ['web'], 'namespace' => \Baytek\Laravel\Users\Controllers::class], function ($router)
        {
            Auth::routes();
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(PermissionServiceProvider::class);
    }
}