<?php

namespace Baytek\Laravel\Users;

use Baytek\Laravel\Content\Installer;
use Baytek\Laravel\Users\Seeders\AllSeeder;
use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\ServiceProvider;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Artisan;
use DB;

class UserInstaller extends Installer
{
    public $name = 'User';
    protected $provider = ServiceProvider::class;
    protected $model = User::class;
    protected $seeder = AllSeeder::class;

    public function beforeInstallation()
    {
        $pluginTables = [
            env('DB_PREFIX', '').'roles',
            env('DB_PREFIX', '').'permissions',
        ];

        $shouldPublish = collect(array_map('reset', DB::select('SHOW TABLES')))
            ->intersect($pluginTables)
            ->isEmpty();

        Artisan::call('optimize');

        if($shouldPublish && !class_exists(\CreatePermissionTables::class)) {
            Artisan::call('vendor:publish', ['--tag' => 'migrations', '--provider' => \Spatie\Permission\PermissionServiceProvider::class]);
        }

        // Run migration, perhaps we should ask the user if they out like to migrate,
        Artisan::call('migrate');
    }

    public function shouldPublish()
    {
        return true;
    }

    public function shouldMigrate()
    {
        return false;
    }

    public function shouldProtect()
    {
        foreach(['view', 'create', 'update', 'delete'] as $permission) {

            // If the permission exists in any form do not reseed.
            if(Permission::where('name', title_case($permission.' '.$this->name))->exists()) {
                return false;
            }
        }

        return true;
    }

    public function shouldSeed()
    {
        return empty(Role::where('name', 'Root')->first());
    }
}
