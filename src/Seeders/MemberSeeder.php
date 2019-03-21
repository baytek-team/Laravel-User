<?php
namespace Baytek\Laravel\Users\Seeders;

use Baytek\Laravel\Content\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MemberSeeder extends Seeder
{
    private $data = [
        [
            'key' => 'member-menu',
            'title' => 'member Navigation Menu',
            'content' => '',
            'relations' => [
                ['content-type', 'menu'],
                ['parent-id', 'admin-menu'],
            ]
        ],
        [
            'key' => 'member-index',
            'title' => 'Members',
            'content' => 'members.index',
            'meta' => [
                'type' => 'route',
                'class' => 'item',
                'append' => '</span>',
                'prepend' => '<i class="left users icon"></i><span class="collapseable-text">',
                'permission' => 'View Member'
            ],
            'relations' => [
                ['content-type', 'menu-item'],
                ['parent-id', 'member-menu'],
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(['member'] as $role) {
            Role::create(['name' => ucfirst($role)]);
        }

        $this->seedStructure($this->data);
    }
}
