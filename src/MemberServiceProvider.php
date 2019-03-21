<?php

namespace Baytek\Laravel\Users;

use Baytek\Laravel\Users\Policies\MemberPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

use Route;

class MemberServiceProvider extends AuthServiceProvider
{

    /**
     * List of artisan commands provided by this package
     * @var Array
     */
    protected $commands = [
        Commands\MemberInstaller::class,
    ];

    protected $policies = [
        Member::class => MemberPolicy::class,
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
        $this->loadViewsFrom(__DIR__.'/../views', 'members');

        // Set the path to publish assets for members to extend
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/members'),
        ], 'views');

        // Publish routes to the App
        $this->publishes([
            __DIR__.'/../src/Routes' => base_path('routes'),
        ], 'routes');

        $this->publishes([
            __DIR__.'/../config/member.php' => config_path('member.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);

        // $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);
    }
}
