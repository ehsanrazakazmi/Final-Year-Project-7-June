<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Role guard for token-authenticated API routes.
 *
 * The three portal guards (EnsureRole) cannot be reused here: they redirect
 * to a portal and call session()->invalidate(), neither of which means
 * anything to a stateless token request. This one answers with JSON.
 *
 * auth:sanctum establishes *who* the caller is; it says nothing about what
 * they may do. Without this, every endpoint was open to any account that
 * could log in - a resident could list every order in the system or create
 * service categories.
 *
 * Usage: ->middleware('api.role:admin') or 'api.role:admin,technician'
 */
class EnsureApiRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        // roleName() is Spatie-first with a fallback to the legacy `role`
        // column, the same resolution the web guards use, so a user cannot be
        // one role to the portal and another to the API.
        if (! in_array($user->roleName(), $roles, true)) {
            abort(403, 'This account is not allowed to perform that action.');
        }

        return $next($request);
    }
}
