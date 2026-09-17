<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Order matters: the roles must exist before a user can be given one.
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
