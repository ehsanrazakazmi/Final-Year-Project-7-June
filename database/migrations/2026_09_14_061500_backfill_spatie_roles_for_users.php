<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Gives every existing user the Spatie role matching their legacy `role`
 * integer (1=admin, 2=resident, 3=technician).
 */
return new class extends Migration
{
    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (User::ROLE_MAP as $name) {
            Role::findOrCreate($name, 'web');
        }

        User::query()->select(['id', 'role'])->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $name = User::roleNameFor($user->role);

                if ($name !== null) {
                    $user->syncRoles([$name]);
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->delete();
    }
};
