<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AccountInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Admin-only user management.
 *
 * Admins create accounts by supplying a name, an email and a role; they never
 * set or see a password. The new user receives an invitation email and chooses
 * their own password through SetPasswordController.
 */
class AdminUserController extends Controller
{
    /**
     * The three fixed roles an admin may assign.
     *
     * @return array<int, string>
     */
    protected function assignableRoles(): array
    {
        return array_values(User::ROLE_MAP);
    }

    protected function broker()
    {
        return Password::broker('invitations');
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $role = (string) $request->query('role', '');

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(in_array($role, $this->assignableRoles(), true), function ($query) use ($role) {
                $query->whereHas('roles', fn ($q) => $q->where('name', $role));
            })
            ->with('roles')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('adminpanel.users.index', [
            'users' => $users,
            'roles' => $this->assignableRoles(),
            'search' => $search,
            'role' => $role,
        ]);
    }

    public function create()
    {
        return view('adminpanel.users.create', [
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in($this->assignableRoles())],
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        // Placeholder only - unusable until the invitee sets their own.
        $user->password = Hash::make(Str::random(64));
        $user->role = User::roleValueFor($data['role']);
        $user->save();

        $user->assignRoleByName($data['role']);

        $sent = $this->sendInvitation($user, $data['role']);

        return redirect()
            ->route('adminpanel.users.index')
            ->with('success', $sent
                ? "{$user->name} was created and an invitation email has been sent."
                : "{$user->name} was created, but the invitation email could not be sent. Use \"Resend invitation\" once mail is working.");
    }

    public function edit(User $user)
    {
        return view('adminpanel.users.edit', [
            'user' => $user,
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in($this->assignableRoles())],
        ]);

        // Don't let the last admin demote themselves out of the admin portal.
        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()
                ->withInput()
                ->withErrors(['role' => 'You cannot change your own role away from admin.']);
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        $user->assignRoleByName($data['role']);

        return redirect()
            ->route('adminpanel.users.index')
            ->with('success', "{$user->name} was updated.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return back()->withErrors(['user' => 'You cannot delete the only remaining admin.']);
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('adminpanel.users.index')
            ->with('success', "{$name} was deleted.");
    }

    /**
     * Re-send the invitation for someone who has not set a password yet.
     */
    public function resendInvitation(User $user)
    {
        $sent = $this->sendInvitation($user, $user->roleName() ?? 'resident');

        return back()->with('success', $sent
            ? "A fresh invitation has been sent to {$user->email}."
            : "The invitation to {$user->email} could not be sent - check the mail configuration.");
    }

    /**
     * Issue a token and email it. Returns false if the mail transport failed,
     * so a broken SMTP setup never loses the created user.
     */
    protected function sendInvitation(User $user, string $roleName): bool
    {
        try {
            $token = $this->broker()->createToken($user);
            $user->notify(new AccountInvitation($token, $roleName));

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}
