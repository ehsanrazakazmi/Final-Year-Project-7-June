<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Portals;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

/**
 * Lets a user invited by an admin choose their own password. The screen is
 * rendered inside the layout of the portal the user belongs to, so a
 * technician sees the technician template and a resident the resident one.
 */
class SetPasswordController extends Controller
{
    /**
     * The invitation broker - longer lived tokens than password resets.
     */
    protected function broker()
    {
        return Password::broker('invitations');
    }

    /**
     * Role-specific view, so each portal keeps its own look.
     */
    protected function viewForRole(?string $role): string
    {
        return match ($role) {
            'admin' => 'auth.set-password.admin',
            'technician' => 'auth.set-password.technician',
            default => 'auth.set-password.resident',
        };
    }

    /**
     * GET - show the form if the invitation token is still good.
     */
    public function show(Request $request, string $token)
    {
        $email = (string) $request->query('email', '');
        $user = User::where('email', $email)->first();

        if (! $user || ! $this->broker()->tokenExists($user, $token)) {
            return redirect()->route('login')->withErrors([
                'email' => 'This invitation link is invalid or has expired. Please ask an administrator to send a new one.',
            ]);
        }

        return view($this->viewForRole($user->roleName()), [
            'token' => $token,
            'email' => $email,
            'user' => $user,
        ]);
    }

    /**
     * POST - store the chosen password, verify the address and sign them in.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = $this->broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ]);

                // Following the emailed link proves they own the address.
                if (is_null($user->email_verified_at)) {
                    $user->forceFill(['email_verified_at' => now()]);
                }

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        $user = User::where('email', $request->input('email'))->first();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->to(Portals::homeForUser($user))
            ->with('success', 'Your password has been set. Welcome to '.config('app.name').'!');
    }
}
