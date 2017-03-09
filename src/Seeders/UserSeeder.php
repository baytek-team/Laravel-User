<?php
namespace Baytek\Laravel\Users\Seeders;

use Baytek\Laravel\Users\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the root account
        DB::table('users')->insert([
            'name' => 'Root',
            'email' => 'webmaster@baytek.ca',
            'password' => bcrypt('aaanne95'),
        ]);

        // Find the root role
        $rootRole = Role::findByName('Root');

        // Assign the root role to the root account
        User::find(1)->assignRole($rootRole);

        // Assign all permissions to Root Role
        $rootRole->permissions()->saveMany(Permission::all());

        if(!in_array(config('app.env'), ['prod', 'production', 'live'])) {
            DB::table('users')->insert([
                [
                    'name' => 'Yvon Viger',
                    'email' => 'yvon@baytek.ca',
                    'password' => bcrypt('aaanne95'),
                ],
                [
                    'name' => 'Chad Sehn',
                    'email' => 'chad@baytek.ca',
                    'password' => bcrypt('aaanne95'),
                ],
                [
                    'name' => 'Sarah Marinoff',
                    'email' => 'sarah@baytek.ca',
                    'password' => bcrypt('aaanne95'),
                ]
            ]);

            // Find the admin role
            $adminRole = Role::findByName('Administrator');

            // Assign the admin role to the baytek accounts
            User::find([1,2,3])->each(function ($user) use ($adminRole) {
                $user->assignRole($rootRole);
                $user->assignRole($adminRole);
            });

            // Assign all permissions to admin Role
            $adminRole->permissions()->saveMany(Permission::all());
        }
    }
}
