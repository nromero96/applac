<?php

namespace Database\Seeders;

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
        $role1 = Role::create(['name' => 'Administrator']);
        $role2 = Role::create(['name' => 'Employee']);
        $role3 = Role::create(['name' => 'Customer']);

        Permission::create(['name' => 'dashboard.index',
                            'description' => 'View Dashboard'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'users.index',
                            'description' => 'List Users'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'users.create',
                            'description' => 'Create User'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'users.edit',
                            'description' => 'Edit User'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'users.destroy',
                            'description' => 'Delete User'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'roles.index',
                            'description' => 'List Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'roles.create',
                            'description' => 'Create Role'])->syncRoles([$role1]);
        Permission::create(['name' => 'roles.edit',
                            'description' => 'Edit Role'])->syncRoles([$role1]);
        Permission::create(['name' => 'roles.destroy',
                            'description' => 'Delete Role'])->syncRoles([$role1]);

        Permission::create(['name' => 'quotations.index',
                            'description' => 'Quotations List'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'quotations.create',
                            'description' => 'Create Quotation'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'quotations.edit',
                            'description' => 'Edit Quotation'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'quotations.destroy',
                            'description' => 'Delete Quotation'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'suppliers.index',
                            'description' => 'List Suppliers'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'suppliers.create',
                            'description' => 'Create Supplier'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'suppliers.edit',
                            'description' => 'Edit Supplier'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'suppliers.destroy',
                            'description' => 'Delete Supplier'])->syncRoles([$role1, $role2]);


        Permission::create(['name' => 'customers.index',
                            'description' => 'List CustomerS'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'customers.create',
                            'description' => 'Create Customer'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'customers.edit',
                            'description' => 'Edit Customer'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'customers.destroy',
                            'description' => 'Delete Customer'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'calendar.index',
                            'description' => 'View Calendar'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'calendar.create',
                            'description' => 'Create Event'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'calendar.edit',
                            'description' => 'Edit Event'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'calendar.destroy',
                            'description' => 'Delete Event'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'notes.index',
                            'description' => 'List Notes'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'notes.create',
                            'description' => 'Create Note'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'notes.edit',
                            'description' => 'Edit Note'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'notes.destroy',
                            'description' => 'Delete Note'])->syncRoles([$role1, $role2]);

    }
}
