<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Signing out used to be GET /logout, declared inside the
 * ['admin','verified'] route group. Two things followed from that:
 *
 *  - Residents and technicians could not log out at all. The admin guard
 *    rejected them and sent them back to their own portal, so the Sign Out
 *    link simply reloaded the dashboard.
 *  - Any page anywhere could log an admin out with <img src="/logout">,
 *    because a GET has no CSRF token to check.
 *
 * Logout is now the POST route Auth::routes() already registered.
 */
class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string}>
     */
    public static function roleProvider(): array
    {
        return [
            'admin' => ['admin'],
            'resident' => ['resident'],
            'technician' => ['technician'],
        ];
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRoleByName($role);

        return $user->fresh();
    }

    /** @dataProvider roleProvider */
    public function test_every_role_can_log_out(string $role): void
    {
        $user = $this->userWithRole($role);

        $this->actingAs($user)->post('/logout')->assertRedirect();

        $this->assertGuest();
    }

    /**
     * The old GET route is gone. Leaving it registered would keep the
     * CSRF-free logout reachable even though nothing links to it any more.
     */
    public function test_logout_is_not_reachable_by_get(): void
    {
        $user = $this->userWithRole('admin');

        $this->actingAs($user)->get('/logout')->assertStatus(405);

        $this->assertAuthenticated();
    }

    /**
     * The point of moving to POST is CSRF protection, so assert the route
     * actually carries it. VerifyCsrfToken short-circuits whenever the app
     * runs in the testing environment, so sending a tokenless request here
     * would prove nothing - the middleware stack is what can be checked.
     */
    public function test_logout_is_csrf_protected(): void
    {
        $route = collect(app('router')->getRoutes())
            ->first(fn ($r) => $r->uri() === 'logout' && in_array('POST', $r->methods(), true));

        $this->assertNotNull($route, 'POST /logout is not registered.');
        $this->assertContains('web', $route->gatherMiddleware());

        // gatherMiddleware() reports the group name, so expand it.
        $webGroup = app(\Illuminate\Contracts\Http\Kernel::class)
            ->getMiddlewareGroups()['web'];

        $this->assertContains(\App\Http\Middleware\VerifyCsrfToken::class, $webGroup);
    }
}
