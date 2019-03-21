<?php

namespace Baytek\Laravel\Users\Commands;

use Baytek\Laravel\Content\Commands\Installer;

use Baytek\Laravel\Users\Seeders\MemberSeeder;
use Baytek\Laravel\Users\Seeders\FakeDataSeeder;
use Baytek\Laravel\Users\Member;
use Baytek\Laravel\Users\MemberServiceProvider;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Artisan;
use DB;

class MemberInstaller extends Installer
{
    public $name = 'Member';
    protected $protected = ['Member'];
    protected $provider = MemberServiceProvider::class;
    protected $model = Member::class;
    protected $seeder = MemberSeeder::class;
    protected $fakeSeeder = FakeDataSeeder::class;
    protected $migrationPath = __DIR__.'/../resources/Database/Migrations';

    public function shouldPublish()
    {
        return true;
    }

    public function shouldMigrate()
    {
        $pluginTables = [
            env('DB_PREFIX', '').'users',
            env('DB_PREFIX', '').'user_meta',
            env('DB_PREFIX', '').'roles',
            env('DB_PREFIX', '').'permissions',
        ];

        return collect(array_map('reset', DB::select('SHOW TABLES')))
            ->intersect($pluginTables)
            ->isEmpty();
    }

    public function shouldSeed()
    {
        return empty(Role::where('name', 'Member')->first());
    }

    public function shouldProtect()
    {
        foreach ($this->protected as $model) {
            foreach(['view', 'create', 'update', 'delete'] as $permission) {

                // If the permission exists in any form do not reseed.
                if(Permission::where('name', title_case($permission.' '.$model))->exists()) {
                    return false;
                }
            }
        }

        return true;
    }
}
