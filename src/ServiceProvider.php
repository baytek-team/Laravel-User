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

    /**
     * List of artisan commands provided by this package
     * @var Array
     */
    protected $commands = [
        Commands\UserInstaller::class,
    ];

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

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../views', 'user');

        $this->publishes([
            __DIR__.'/../config/user.php' => config_path('user.php'),
        ], 'config');

        // Publish routes to the App
        $this->publishes([
            __DIR__.'/../src/Routes' => base_path('routes'),
        ], 'routes');

        // Set the path to publish assets for users to extend
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/user'),
        ], 'views');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
        $this->app->register(MemberServiceProvider::class);
    }
}
