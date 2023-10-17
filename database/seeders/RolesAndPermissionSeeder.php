<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions 
        // https://spatie.be/docs/laravel-permission/v5/advanced-usage/seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        /**
         * Roles and permission creations.
         */

        // Roles for common and admin users
        Role::firstOrCreate(['name' => 'super-admin']);
        $common = Role::firstOrCreate(['name' => 'common']);
        $duoc   = Role::firstOrCreate(['name' => 'duoc']);

        // Academic Charges Permissions
        Permission::firstOrCreate(['name' => 'academic-charges.index']);
        Permission::firstOrCreate(['name' => 'academic-charges.create']);
        Permission::firstOrCreate(['name' => 'academic-charges.update']);
        Permission::firstOrCreate(['name' => 'academic-charges.destroy']);

        // User Permissions
        Permission::firstOrCreate(['name' => 'users.index']);
        Permission::firstOrCreate(['name' => 'users.create']);
        Permission::firstOrCreate(['name' => 'users.update']);
        // Permission to update user roles
        Permission::firstOrCreate(['name' => 'users.roles.update']);

        /**
         * Permissions sync with roles.
         */

        $duoc->syncPermissions([
            'academic-charges.index',   
            'academic-charges.create',
            'academic-charges.update',
            'academic-charges.destroy',
            // User management permission
            'users.index',
            'users.create',
            'users.update',
            'users.roles.update',
        ]);

        $common->syncPermissions([
            'academic-charges.index',
        ]);

        // Create an user using the firebase test user uid environment variable
        if (env('FIREBASE_TEST_USER_UID')) {
            $user = User::firstOrCreate(['id' => env('FIREBASE_TEST_USER_UID')]);
            $user->assignRole('super-admin');
        }
    }
}
