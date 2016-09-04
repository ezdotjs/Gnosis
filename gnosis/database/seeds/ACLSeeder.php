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
         * Roles
         */
        Role::firstOrCreate([
            'name'      => 'super_admin',
            'label'     => 'Super Admin',
            'visible'   => false,
            'protected' => true
        ]);

        Role::firstOrCreate([
            'name'      => 'admin',
            'label'     => 'Admin',
            'visible'   => true,
            'protected' => false
        ]);

        Role::firstOrCreate([
            'name'      => 'developer',
            'label'     => 'Developer',
            'visible'   => true,
            'protected' => false
        ]);

        Role::firstOrCreate([
            'name'      => 'contributor',
            'label'     => 'Contributor',
            'visible'   => true,
            'protected' => false
        ]);

        Role::firstOrCreate([
            'name'      => 'subscriber',
            'label'     => 'Subscriber',
            'visible'   => true,
            'protected' => false
        ]);

        /**
         * Permissions
         */

        // General permissions
        Permission::firstOrCreate([
            'name'  => 'cms',
            'label' => 'Access the CMS'
        ]);

        // User model permissions
        Permission::firstOrCreate([
            'name'  => 'users.list',
            'label' => 'View a list of all users'
        ]);

        Permission::firstOrCreate([
            'name'  => 'users.view',
            'label' => 'View details about a specific user'
        ]);

        Permission::firstOrCreate([
            'name'  => 'users.create',
            'label' => 'Create a new user'
        ]);

        Permission::firstOrCreate([
            'name'  => 'users.update',
            'label' => 'Update a specific user'
        ]);

        Permission::firstOrCreate([
            'name'  => 'users.delete',
            'label' => 'Delete a user'
        ]);

        /**
         * Assign permissions to roles
         */

        //TODO: Add relations here
    }
}
