<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // This seeder runs every time because the roles and permission
        // may change in the future.
        $this->call([
            RolesAndPermissionSeeder::class,
        ]);


        // Seeders to run when the environment is not production. Fake or test data
        if (!app()->environment('production')) {
            $this->call(CsvDataSeeder::class);
        }
    }
}
