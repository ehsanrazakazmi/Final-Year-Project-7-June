<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Support\Portals;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Authentication itself is handled by the AuthenticatesUsers trait, which
    | brings login throttling, session regeneration, "remember me" and proper
    | validation errors. Only the post-login destination is customised here.
    |
    */

    use AuthenticatesUsers;

    /**
     * Fallback redirect; the real destination comes from authenticated().
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Send the user to the portal matching their role.
     */
    protected function authenticated(Request $request, $user)
    {
        $home = Portals::homeForUser($user);

        if ($home === '/login') {
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has no portal assigned. Please contact an administrator.',
            ]);
        }

        return redirect()->to($home);
    }
}
