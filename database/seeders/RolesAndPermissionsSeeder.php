<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
                Role::create(['name' => 'Partner Operator']);
        $role->givePermissionTo(Permission::all());
    }
}
