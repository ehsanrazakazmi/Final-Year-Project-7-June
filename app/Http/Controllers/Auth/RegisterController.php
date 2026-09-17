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

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | Public self-registration. Always creates a RESIDENT - the role is never
    | read from the request, so a visitor cannot choose which portal they land
    | in. Admin and technician accounts are created by an admin through
    | AdminUserController, which emails an invitation.
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
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function create(array $data)
    {
        // The role is NOT taken from the request. Public signup always creates
        // a resident; admin and technician accounts are created by an admin
        // through AdminUserController. Assigning it explicitly (rather than by
        // mass assignment) keeps working now that `role` is not fillable.
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = User::ROLE_RESIDENT;
        $user->save();

        $user->assignRoleByName('resident');

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
