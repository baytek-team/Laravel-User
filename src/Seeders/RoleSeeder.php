<?php
namespace Baytek\Laravel\Users\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(['root', 'administrator', 'editor', 'member'] as $role) {
            Role::create(['name' => ucfirst($role)]);
        }

        foreach(['role'] as $permission) {
            Permission::create(['name' => ucwords('view '   . $permission)]);
            Permission::create(['name' => ucwords('create ' . $permission)]);
            Permission::create(['name' => ucwords('update ' . $permission)]);
            Permission::create(['name' => ucwords('delete ' . $permission)]);
        }
    }
}
