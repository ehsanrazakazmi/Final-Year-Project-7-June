<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Guarantees there is always one admin after `migrate:fresh --seed`.
 *
 * Every other account is created by an admin through AdminUserController, which
 * emails an invitation - so without this seeder a fresh database would have no
 * one able to log in and create the first user.
 *
 * The password is generated fresh on every seed and printed once to the
 * console. Nothing here is read from .env, so the credentials live entirely in
 * this file. To pin a fixed password instead, replace the PASSWORD_LENGTH line
 * with e.g.  $password = 'your-password';
 */
class AdminUserSeeder extends Seeder
{
    private const NAME = 'Administrator';
    private const EMAIL = 'admin@gharbaar.local';
    private const PASSWORD_LENGTH = 8;

    public function run(): void
    {
        $user = User::where('email', self::EMAIL)->first();

        if ($user) {
            // Deliberately does NOT touch the password. Re-running the seeder on
            // an existing database must never silently reset a working login.
            $user->role = User::ROLE_ADMIN;
            $user->email_verified_at = $user->email_verified_at ?? now();
            $user->save();
            $user->assignRoleByName('admin');

            $this->command?->info('Admin already present: '.self::EMAIL.' (password left unchanged)');

            return;
        }

        $password = Str::random(self::PASSWORD_LENGTH);

        $user = new User();
        $user->name = self::NAME;
        $user->email = self::EMAIL;
        $user->password = Hash::make($password);
        $user->role = User::ROLE_ADMIN;
        // Seeded by hand, so there is no invitation email to prove ownership.
        $user->email_verified_at = now();
        $user->save();

        $user->assignRoleByName('admin');

        $this->command?->newLine();
        $this->command?->info('  Admin created');
        $this->command?->line('    email    : '.self::EMAIL);
        $this->command?->line('    password : '.$password);
        $this->command?->warn('    Shown once - copy it now.');
        $this->command?->newLine();
    }
}
