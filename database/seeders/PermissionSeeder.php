<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'title' => Permission::INDEX_ADMIN,
            ],
            [
                'title' => Permission::SHOW_ADMIN,
            ],
            [
                'title' => Permission::CREATE_ADMIN,
            ],
            [
                'title' => Permission::EDIT_ADMIN,
            ],
            [
                'title' => Permission::DELETE_ADMIN,
            ],
            [
                'title' => Permission::RESTORE_ADMIN,
            ],




            
            [
                'title' => Permission::INDEX_ROLE,
            ],
            [
                'title' => Permission::SHOW_ROLE,
            ],
            [
                'title' => Permission::CREATE_ROLE,
            ],
            [
                'title' => Permission::EDIT_ROLE,
            ],
            [
                'title' => Permission::DELETE_ROLE,
            ],
            [
                'title' => Permission::RESTORE_ROLE,
            ],


            [
                'title' => Permission::INDEX_PERMISSION,
            ],
            [
                'title' => Permission::SHOW_PERMISSION,
            ],


            [
                'title' => Permission::SYNC_PERMISSION_ROLE,
            ],
            [
                'title' => Permission::INDEX_PERMISSION_ROLE,
            ],
            [
                'title' => Permission::SHOW_PERMISSION_ROLE,
            ],
            [
                'title' => Permission::CREATE_PERMISSION_ROLE,
            ],
            [
                'title' => Permission::EDIT_PERMISSION_ROLE,
            ],
            [
                'title' => Permission::DELETE_PERMISSION_ROLE,
            ],
            [
                'title' => Permission::RESTORE_PERMISSION_ROLE,
            ],

            
            [
                'title' => Permission::SYNC_ADMIN_ROLE,
            ],
            [
                'title' => Permission::INDEX_ADMIN_ROLE,
            ],
            [
                'title' => Permission::SHOW_ADMIN_ROLE,
            ],
            [
                'title' => Permission::CREATE_ADMIN_ROLE,
            ],
            [
                'title' => Permission::EDIT_ADMIN_ROLE,
            ],
            [
                'title' => Permission::DELETE_ADMIN_ROLE,
            ],
            [
                'title' => Permission::RESTORE_ADMIN_ROLE,
            ],

        ];

        Permission::upsert($permissions, ['title']);

    }
}
