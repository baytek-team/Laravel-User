<?php

namespace Baytek\Laravel\Users;

use Baytek\Laravel\Users\Policies\PermissionPolicy;
use Baytek\Laravel\Users\Policies\RolePolicy;
use Baytek\Laravel\Users\Policies\UserPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionServiceProvider;

use Route;

class ServiceProvider extends AuthServiceProvider
{

    protected $policies = [
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

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

        // Set the path to publish assets for users to extend
        $this->publishes([
            __DIR__.'/../resources/Views' => resource_path('views/vendor/user'),
        ], 'views');

        (new UserInstaller)->installCommand();
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
