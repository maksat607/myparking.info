<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $arrayOfPermissionNames = [
            'user_view',
            'user_create',
            'user_update',
            'user_delete',
            'permission_update',
            'legal_view',
            'legal_create',
            'legal_update',
            'legal_delete',
            'parking_view',
            'parking_create',
            'parking_update',
            'parking_delete',
            'partner_view',
            'partner_create',
            'partner_update',
            'partner_type_view',
            'partner_type_create',
            'partner_type_update',
            'application_view',
            'application_create',
            'application_update',
            'application_delete',
            'application_to_accepted',
            'application_to_issue',
            'application_to_inspection',
            'application_issue',

            "notify_app_pending","notify_app_storage","notify_app_issuance","notify_app_issued",
            "notify_app_denied-for-storage,cancelled-by-partner,cancelled-by-us","notify_app_deleted",
            "notify_app_viewRequest1","notify_app_viewRequest2","notify_app_viewRequest3","notify_app_moderation",

        ];

        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());

        $role = Role::create(['name' => 'SuperAdmin']);
                Role::create(['name' => 'Admin']);
                Role::create(['name' => 'Manager']);
                Role::create(['name' => 'Operator']);
                Role::create(['name' => 'Partner']);
                Role::create(['name' => 'PartnerOperator']);
                Role::create(['name' => 'Moderator']);
        $role->givePermissionTo(Permission::all());
    }
}
