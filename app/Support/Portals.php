<?php

namespace App\Support;

use App\Models\User;

/**
 * Single source of truth for "which portal does this role land on".
 *
 * This mapping used to be restated in LoginController and in each of the three
 * role middleware, which is how the admin/resident redirects drifted out of
 * sync. Everything now resolves through here.
 */
class Portals
{
    /**
     * Spatie role name => portal landing path.
     *
     * @var array<string, string>
     */
    public const HOME = [
        'admin' => '/adminpanel',
        'resident' => '/pages/home',
        'technician' => '/technicianpanel',
    ];

    /**
     * Landing path for a role name, or the login page when unknown.
     */
    public static function homeFor(?string $role): string
    {
        return self::HOME[$role] ?? '/login';
    }

    /**
     * Landing path for a user, based on their Spatie role.
     */
    public static function homeForUser(?User $user): string
    {
        return self::homeFor($user?->roleName());
    }
}
