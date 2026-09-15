<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\TestMail;
use App\Http\Controllers\Controller;
use App\Support\Portals;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | Public self-registration. NOTE: the role is still taken from the request,
    | so a visitor can choose which portal they land in. That is a known open
    | issue - account creation is meant to move behind AdminUserController.
    |
    */

    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys(User::ROLE_MAP))],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => (int) $data['role'],
        ]);

        $roleName = User::roleNameFor((int) $data['role']);

        if ($roleName !== null) {
            $user->assignRoleByName($roleName);
        }

        // A dead mail transport must not destroy an account that already exists.
        try {
            Mail::to($user->email)->send(new TestMail($user));
        } catch (\Throwable $e) {
            report($e);
        }

        return $user;
    }

    /**
     * Overridden so a mail failure in the Registered listener (the verification
     * email) cannot 500 the request after the user row has been written.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        try {
            event(new Registered($user));
        } catch (\Throwable $e) {
            report($e);
        }

        $this->guard()->login($user);

        if ($request->wantsJson()) {
            return new JsonResponse([], 201);
        }

        return redirect()->to(Portals::homeForUser($user));
    }
}
