<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Public signup must never let a visitor choose their own role.
 *
 * The register form used to carry a free-text `role` input, and the controller
 * passed it straight through - so anyone could register as an administrator.
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_register_and_becomes_a_resident(): void
    {
        $this->post('/register', [
            'name' => 'Ordinary Visitor',
            'email' => 'visitor@example.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertRedirect('/pages/home');

        $user = User::where('email', 'visitor@example.test')->firstOrFail();

        $this->assertSame(User::ROLE_RESIDENT, $user->role);
        $this->assertTrue($user->hasRole('resident'));
    }

    public function test_a_forged_role_in_the_request_is_ignored(): void
    {
        $this->post('/register', [
            'name' => 'Sneaky Visitor',
            'email' => 'sneaky@example.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'role' => User::ROLE_ADMIN,   // the escalation attempt
        ]);

        $user = User::where('email', 'sneaky@example.test')->firstOrFail();

        $this->assertFalse($user->hasRole('admin'), 'a registrant became an admin');
        $this->assertSame(User::ROLE_RESIDENT, $user->role);
    }

    public function test_role_is_not_mass_assignable(): void
    {
        // Guards the other half: User::create($request->all()) must not be able
        // to set a role either.
        $user = User::create([
            'name' => 'Mass Assign',
            'email' => 'mass@example.test',
            'password' => 'irrelevant',
            'role' => User::ROLE_ADMIN,
        ]);

        $this->assertNotSame(User::ROLE_ADMIN, $user->role);
    }

    public function test_the_register_form_has_no_role_field(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertDontSee('name="role"', false);
    }
}
