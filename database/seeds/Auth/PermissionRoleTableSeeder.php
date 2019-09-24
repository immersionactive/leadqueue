<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{

    use DisableForeignKeys;

    public function run()
    {

        $this->disableForeignKeys();

        /**
         * Create permissions
         */

        Permission::create(['name' => 'view backend']);

        Permission::create(['name' => 'client.index']);
        Permission::create(['name' => 'client.show']);
        Permission::create(['name' => 'client.create']);
        Permission::create(['name' => 'client.update']);
        Permission::create(['name' => 'client.destroy']);

        Permission::create(['name' => 'client.lead_source.index']);
        Permission::create(['name' => 'client.lead_source.show']);
        Permission::create(['name' => 'client.lead_source.create']);
        Permission::create(['name' => 'client.lead_source.update']);
        Permission::create(['name' => 'client.lead_source.destroy']);

        /**
         * Create roles and assign corresponding permissions
         */

        // Superuser

        $admin_role = Role::create(['name' => config('access.users.admin_role')]);
        // There's no need to assign individual permissions to the superuser
        // role - the admin role automatically has permission to do everything

        // Strategist

        $strategist_role = Role::create(['name' => 'strategist']);

        $strategist_role->givePermissionTo('view backend');

        $strategist_role->givePermissionTo('client.index');
        $strategist_role->givePermissionTo('client.show');

        // Assign Permissions to other Roles
        // Note: Admin (User 1) Has all permissions via a gate in the AuthServiceProvider
        // $user->givePermissionTo('view backend');

        $this->enableForeignKeys();

    }

}
