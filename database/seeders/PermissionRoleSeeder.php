<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $adminPermissions = Permission::all();

        $managerPermissions = $adminPermissions->filter(function ($permission) {
            return substr($permission->title, 0, 6) != 'DELETE' &&
                   strpos($permission->title, 'RESTORE') === false &&
                   strpos($permission->title, 'ROLE') === false &&
                   strpos($permission->title, 'PERMISSION') === false &&
                   strpos($permission->title, 'CREATE_ADMIN') === false &&
                   strpos($permission->title, 'EDIT_ADMIN') === false &&
                //    strpos($permission->title, 'DELETE_ADMIN') === false &&       // because we already flagged delete above
                //    strpos($permission->title, 'RESTORE_ADMIN') === false &&      // because we already flagged restore above
                   strpos($permission->title, 'EDIT_INVOICE') === false &&
                   strpos($permission->title, 'EDIT_CONSTANT') === false &&
                   strpos($permission->title, 'SEND') === false;
        });

        $financePermissions = $adminPermissions->filter(function ($permission) {
            return substr($permission->title, 0, 6) != 'DELETE' &&
                   strpos($permission->title, 'RESTORE') === false &&
                   strpos($permission->title, 'ROLE') === false &&
                   strpos($permission->title, 'PERMISSION') === false &&
                   strpos($permission->title, 'CREATE_ADMIN') === false &&
                   strpos($permission->title, 'EDIT_ADMIN') === false &&
                //    strpos($permission->title, 'DELETE_ADMIN') === false &&       // because we already flagged delete above
                //    strpos($permission->title, 'RESTORE_ADMIN') === false &&      // because we already flagged restore above
                   strpos($permission->title, 'EDIT_INVOICE') === false &&
                   strpos($permission->title, 'EDIT_CONSTANT') === false &&
                   strpos($permission->title, 'SEND') === false;
        });

        $systemUserPermissions = $adminPermissions->filter(function ($permission) {
            return substr($permission->title, 0, 6) != 'DELETE' &&
            strpos($permission->title, 'RESTORE') === false &&
            strpos($permission->title, 'ROLE') === false &&
            strpos($permission->title, 'PERMISSION') === false &&
            strpos($permission->title, 'CREATE_ADMIN') === false &&
            strpos($permission->title, 'EDIT_ADMIN') === false &&
         //    strpos($permission->title, 'DELETE_ADMIN') === false &&       // because we already flagged delete above
         //    strpos($permission->title, 'RESTORE_ADMIN') === false &&      // because we already flagged restore above
            strpos($permission->title, 'INVOICE') === false &&
            strpos($permission->title, 'EDIT_CONSTANT') === false &&
            strpos($permission->title, 'SEND') === false;
        });

        
        Role::where('title', Role::SUPER_ADMIN_ROLE)->firstOrFail()->permissions()->sync($adminPermissions);
        Role::where('title', Role::MANAGER_ROLE)->firstOrFail()->permissions()->sync($managerPermissions);
        Role::where('title', Role::FINANCE_ROLE)->firstOrFail()->permissions()->sync($financePermissions);
        Role::where('title', Role::SYSTEM_USER_ROLE)->firstOrFail()->permissions()->sync($systemUserPermissions);
        

    }
}
