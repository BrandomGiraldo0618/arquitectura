<?php

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

        // create permissions
        Permission::create(['name' => 'index_permissions']);
        Permission::create(['name' => 'create_permissions']);
        Permission::create(['name' => 'update_permissions']);
        Permission::create(['name' => 'delete_permissions']);

        //-- Permissions for users
        Permission::create(['name' => 'index_users']);
        Permission::create(['name' => 'create_users']);
        Permission::create(['name' => 'update_users']);
        Permission::create(['name' => 'delete_users']);

        //-- Permissions for posts
        /* Permission::create(['name' => 'index_posts']);
        Permission::create(['name' => 'create_posts']);
        Permission::create(['name' => 'update_posts']);
        Permission::create(['name' => 'delete_posts']); */

        // Roles por defecto
        $admin = Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Estandar']);

        $admin->syncPermissions(Permission::all()); // Al administrador se le otorgan todos los permisos
    }
}
