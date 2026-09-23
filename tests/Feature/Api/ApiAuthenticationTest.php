<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Every route in routes/api.php except POST /sanctum/token was public,
 * including the writes: anyone who could reach the server could place an
 * order, change any order's status, create a category or empty a wishlist,
 * without ever logging in.
 *
 * These tests pin that shut. If a route is ever added outside the
 * auth:sanctum group, test_no_api_route_is_public() fails and names it.
 */
class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A representative call per HTTP verb, covering reads and writes.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function protectedRouteProvider(): array
    {
        return [
            'read categories' => ['get', '/api/categories'],
            'read orders' => ['get', '/api/orders'],
            'read home data' => ['get', '/api/home_data'],
            'read a table' => ['get', '/api/tables/categories'],
            'read someone\'s wishlist' => ['get', '/api/users/1/wishlists'],
            'read order status' => ['get', '/api/order_status/1'],
            'create a category' => ['post', '/api/category_add'],
            'add to a wishlist' => ['post', '/api/wishlist_add'],
            'place an order' => ['post', '/api/cart_add'],
            'accept an order' => ['put', '/api/orders/1/status'],
            'edit an order' => ['put', '/api/orders/1'],
            'remove from a wishlist' => ['delete', '/api/wishlist_remove/1/1'],
        ];
    }

    /** @dataProvider protectedRouteProvider */
    public function test_api_routes_reject_unauthenticated_callers(string $verb, string $uri): void
    {
        $this->{$verb.'Json'}($uri)->assertUnauthorized();
    }

    /**
     * The token endpoint has to stay open - it is how a token is obtained -
     * but it must still refuse bad credentials.
     */
    public function test_token_endpoint_is_public_but_checks_credentials(): void
    {
        User::factory()->create([
            'email' => 'resident@example.test',
            'password' => bcrypt('correct-horse'),
        ]);

        $this->postJson('/api/sanctum/token', [
            'email' => 'resident@example.test',
            'password' => 'wrong-password',
            'device_name' => 'mobile',
        ])->assertStatus(422);

        $this->postJson('/api/sanctum/token', [
            'email' => 'resident@example.test',
            'password' => 'correct-horse',
            'device_name' => 'mobile',
        ])->assertStatus(201)->assertJsonStructure(['user', 'token']);
    }

    /**
     * The React Native app reads user.role to decide which dashboard to show
     * (src/Routes.js), so the token response must keep carrying it.
     */
    public function test_token_response_still_carries_the_role_the_app_switches_on(): void
    {
        User::factory()->create([
            'email' => 'tech@example.test',
            'password' => bcrypt('secret-pass'),
            'role' => 3,
        ]);

        $this->postJson('/api/sanctum/token', [
            'email' => 'tech@example.test',
            'password' => 'secret-pass',
            'device_name' => 'mobile',
        ])->assertStatus(201)->assertJsonPath('user.role', 3);
    }

    public function test_a_valid_token_gets_through(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/categories')->assertOk();
    }

    /**
     * A guard against the real failure mode: someone appends a route to
     * routes/api.php below the closing brace of the auth group and never
     * notices it is public.
     */
    public function test_no_api_route_is_public(): void
    {
        $allowedPublic = ['api/sanctum/token'];

        $public = [];

        foreach (app('router')->getRoutes() as $route) {
            $uri = $route->uri();

            if (! str_starts_with($uri, 'api/') || str_contains($uri, 'chatify')) {
                continue;
            }

            if (in_array($uri, $allowedPublic, true)) {
                continue;
            }

            if (! in_array('auth:sanctum', $route->gatherMiddleware(), true)) {
                $public[] = implode('|', $route->methods()).' '.$uri;
            }
        }

        $this->assertSame(
            [],
            $public,
            "These API routes are reachable without a token:\n  ".implode("\n  ", $public)
        );
    }
}
