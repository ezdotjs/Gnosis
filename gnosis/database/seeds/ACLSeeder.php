<?php

use Illuminate\Database\Seeder;
use App\Models\Gnosis\Role;
use App\Models\Gnosis\Permission;

class ACLSeeder extends Seeder
{
    /**
     * Seed the database with default roles and permissions
     *
     * @return void
     */
    public function run()
    {
        /**
         * Permissions
         */

        // General
        $perms = [];

        $perms['cms'] = Permission::firstOrCreate([
            'name'  => 'cms',
            'label' => 'Access the CMS'
        ]);

        // User model permissions
        $perms['users.list'] = Permission::firstOrCreate([
            'name'  => 'users.list',
            'label' => 'View a list of all users'
        ]);

        $perms['users.view'] = Permission::firstOrCreate([
            'name'  => 'users.view',
            'label' => 'View details about a specific user'
        ]);

        $perms['users.create'] = Permission::firstOrCreate([
            'name'  => 'users.create',
            'label' => 'Create a new user'
        ]);

        $perms['users.update'] = Permission::firstOrCreate([
            'name'  => 'users.update',
            'label' => 'Update a specific user'
        ]);

        $perms['users.delete'] = Permission::firstOrCreate([
            'name'  => 'users.delete',
            'label' => 'Delete a user'
        ]);

        /**
         * Roles
         */
        $role = Role::firstOrCreate([
            'name'      => 'super_admin',
            'label'     => 'Super Admin',
            'visible'   => false,
            'protected' => true
        ]);

        // Attach all permissions to Super Admin
        $ids = [];
        foreach ($perms as $perm) {
            array_push($ids, $perm->id);
        }
        $role->permissions()->sync($ids);

        $role = Role::firstOrCreate([
            'name'      => 'admin',
            'label'     => 'Admin',
            'visible'   => true,
            'protected' => false
        ]);

        // Attach all permissions to Admin
        $ids = [];
        foreach ($perms as $perm) {
            array_push($ids, $perm->id);
        }
        $role->permissions()->sync($ids);

        $role = Role::firstOrCreate([
            'name'      => 'developer',
            'label'     => 'Developer',
            'visible'   => true,
            'protected' => false
        ]);

        $role = Role::firstOrCreate([
            'name'      => 'contributor',
            'label'     => 'Contributor',
            'visible'   => true,
            'protected' => false
        ]);

        $role = Role::firstOrCreate([
            'name'      => 'subscriber',
            'label'     => 'Subscriber',
            'visible'   => true,
            'protected' => false
        ]);
    }
}
