<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\AccountInvitation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

/**
 * Creates (or re-invites) a user from the CLI and prints the set-password URL.
 *
 * This is how the first admin is bootstrapped, and it is also the way to test
 * the invitation flow while SMTP is unavailable - the link is always printed,
 * whether or not the email goes out.
 */
class InviteUser extends Command
{
    protected $signature = 'user:invite
                            {email : Email address of the user}
                            {--name= : Display name (required when creating)}
                            {--role=resident : admin, resident or technician}';

    protected $description = 'Create a user and issue a password invitation link';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $role = (string) $this->option('role');
        $name = $this->option('name');

        $validator = Validator::make(
            ['email' => $email, 'role' => $role],
            [
                'email' => ['required', 'email'],
                'role' => ['required', Rule::in(array_values(User::ROLE_MAP))],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            if (! $name) {
                $this->error('--name is required when creating a new user.');

                return self::FAILURE;
            }

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make(Str::random(64));
            $user->role = User::roleValueFor($role);
            $user->save();

            $this->info("Created user #{$user->id} ({$email}).");
        } else {
            if ($name) {
                $user->name = $name;
                $user->save();
            }

            $this->info("Found existing user #{$user->id} ({$email}).");
        }

        $user->assignRoleByName($role);
        $this->info("Role set to '{$role}'.");

        $token = Password::broker('invitations')->createToken($user);

        $url = url(route('password.set', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        try {
            $user->notify(new AccountInvitation($token, $role));
            $this->info('Invitation email sent.');
        } catch (\Throwable $e) {
            $this->warn('Invitation email could NOT be sent: '.$e->getMessage());
        }

        $this->newLine();
        $this->line('Set-password link (valid '.round(config('auth.passwords.invitations.expire') / 1440).' days):');
        $this->line($url);
        $this->newLine();

        return self::SUCCESS;
    }
}
