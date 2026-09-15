<?php

namespace App\Http\Middleware;

use App\Support\Portals;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Send an already-authenticated visitor to their own portal rather than to
     * the generic /home page, which belongs to none of the three portals.
     *
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect()->to(Portals::homeForUser(Auth::guard($guard)->user()));
            }
        }

        return $next($request);
    }
}
