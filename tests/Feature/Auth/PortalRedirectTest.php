<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Each role must land on its own portal, and be bounced out of the others.
 *
 * The admin/resident mapping was inverted for most of this project's life
 * because the role -> portal table was duplicated in four files; these tests
 * pin the behaviour to App\Support\Portals.
 */
class PortalRedirectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function roleProvider(): array
    {
        return [
            'admin' => ['admin', '/adminpanel'],
            'resident' => ['resident', '/pages/home'],
            'technician' => ['technician', '/technicianpanel'],
        ];
    }

    /** @dataProvider roleProvider */
    public function test_login_sends_each_role_to_its_own_portal(string $role, string $home): void
    {
        $user = User::factory()->{$role}()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect($home);

        $this->assertAuthenticatedAs($user);
    }

    /** @dataProvider roleProvider */
    public function test_a_role_may_open_its_own_portal(string $role, string $home): void
    {
        $user = User::factory()->{$role}()->create();

        $this->actingAs($user)->get($home)->assertOk();
    }

    public function test_a_technician_can_reach_a_working_technician_page(): void
    {
        $technician = User::factory()->technician()->create();

        $this->actingAs($technician)->get('/technicianpanel/introduction')->assertOk();
    }

    public function test_a_resident_is_bounced_out_of_the_other_portals(): void
    {
        $resident = User::factory()->resident()->create();

        $this->actingAs($resident)->get('/adminpanel')->assertRedirect('/pages/home');
        $this->actingAs($resident)->get('/technicianpanel')->assertRedirect('/pages/home');
    }

    public function test_a_technician_is_bounced_out_of_the_other_portals(): void
    {
        $technician = User::factory()->technician()->create();

        $this->actingAs($technician)->get('/adminpanel')->assertRedirect('/technicianpanel');
        $this->actingAs($technician)->get('/pages/home')->assertRedirect('/technicianpanel');
    }

    public function test_an_authenticated_user_visiting_login_goes_to_their_portal(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/login')->assertRedirect('/adminpanel');
    }

    public function test_a_guest_is_sent_to_login(): void
    {
        $this->get('/adminpanel')->assertRedirect('/login');
    }

    public function test_a_user_with_an_unrecognised_role_is_logged_out_not_looped(): void
    {
        // Regression: the middleware used to fall off the end and return null
        // for any role outside 1-3, which Laravel turns into a 500.
        $stray = User::factory()->create();
        $stray->syncRoles([]);
        $stray->forceFill(['role' => 99])->save();

        $this->actingAs($stray)->get('/adminpanel')->assertRedirect('/login');
        $this->assertGuest();
    }
}
