<?php
namespace Baytek\Laravel\Users\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use Baytek\Laravel\Users\Member;
use Baytek\Laravel\Users\UserMeta;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //First create a generic admin and member, that visitors to
        //a demo site can sign in with and test as
        $this->generateTestAccounts();

        //Then seed the fake members
        $this->generateUsers();
    }

    public function generateTestAccounts()
    {
        /**
         * The fake admin
         */
        $meta = [
            'first_name' => 'Demo',
            'last_name' => 'Admin',
            'work_phone' => '555-555-5555',
            'home_phone' => '123-345-7890',
            'company' => 'Baytek',
            'title' => 'Tester',
        ];

        //Save the user
        $admin = new Member();
        $admin->email = 'admin@example.com';
        $admin->name = 'Demo Admin';
        $admin->password = bcrypt('password');
        $admin->save();

        //Save the user meta
        foreach($meta as $key => $value) {
            $metaRecord = (new UserMeta([
                'language' => 'en',
                'key' => $key,
                'value' => $value
            ]));
            $admin->meta()->save($metaRecord);
            $metaRecord->save();
        }

        $admin->assignRole(['Administrator']);
        $admin->onBit(Member::APPROVED)->update();

        /**
         * The fake member
         */
        $meta = [
            'first_name' => 'Demo',
            'last_name' => 'Member',
            'work_phone' => '555-555-5555',
            'home_phone' => '123-345-7890',
            'company' => 'Baytek',
            'title' => 'Tester',
        ];

        //Save the user
        $member = new Member();
        $member->email = 'member@example.com';
        $member->name = 'Demo Member';
        $member->password = bcrypt('password');
        $member->save();

        //Save the user meta
        foreach($meta as $key => $value) {
            $metaRecord = (new UserMeta([
                'language' => 'en',
                'key' => $key,
                'value' => $value
            ]));
            $member->meta()->save($metaRecord);
            $metaRecord->save();
        }

        $member->assignRole(['Member']);
        $member->onBit(Member::APPROVED)->update();
    }

    public function generateUsers($total = 50)
    {
        $faker = Faker::create();

        foreach (range(1,$total) as $index) {

            //Member info
            $first_name = $faker->firstName;
            $last_name = $faker->lastName;
            $email = $faker->unique()->safeEmail($first_name.' '.$last_name);
            $meta = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'work_phone' => $faker->phoneNumber,
                'home_phone' => $faker->phoneNumber,
                'company' => $faker->company,
                'title' => $faker->jobTitle,
            ];

            //Save the user
            $member = new Member();
            $member->email = $email;
            $member->name = $first_name . ' ' . $last_name;
            $member->password = bcrypt('password');
            $member->save();

            //Save the user meta
            foreach($meta as $key => $value) {
                $metaRecord = (new UserMeta([
                    'language' => 'en',
                    'key' => $key,
                    'value' => $value
                ]));
                $member->meta()->save($metaRecord);
                $metaRecord->save();
            }

            $member->assignRole(['Member']);
            $member->onBit(Member::APPROVED)->update();
        }
    }
}
