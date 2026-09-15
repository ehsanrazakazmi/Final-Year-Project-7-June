<?php

namespace App\Http\Middleware;

use App\Support\Portals;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Shared behaviour for the three portal guards.
 *
 * Authorisation is decided by Spatie roles. A user with a different role is
 * bounced to their own portal; a user with no recognised role is signed out
 * with an explanation rather than falling through and returning null, which
 * is what the previous per-role middleware did.
 */
abstract class EnsureRole
{
    /**
     * The Spatie role this middleware admits.
     */
    abstract protected function role(): string;

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // roleName() is Spatie-first with a fallback to the legacy `role`
        // column. Portals::homeForUser() resolves through the same method, so
        // the guard and the redirect target can never disagree - if they did,
        // a user would bounce between them forever.
        if ($user->roleName() === $this->role()) {
            return $next($request);
        }

        $home = Portals::homeForUser($user);

        if ($home === '/login') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has no portal assigned. Please contact an administrator.',
            ]);
        }

        return redirect()->to($home);
    }
}
