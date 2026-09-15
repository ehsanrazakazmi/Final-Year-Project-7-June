<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * The application's three fixed roles.
     *
     * These are intentionally not manageable through the UI - there is no
     * create/update/delete for roles, only this seeder.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (User::ROLE_MAP as $name) {
            Role::findOrCreate($name, 'web');
        }
    }
}
