<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Order;
use App\Models\Services;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * auth:sanctum proved who the caller was. It did not say what they could do,
 * and every endpoint took the acting user's id from the URL or request body.
 *
 * Signed in as an ordinary resident you could therefore read anyone's
 * wishlist and order history, list every order in the system with its
 * customer's name, email, phone and address, place an order in someone
 * else's name, create service categories, and rewrite any column of any
 * order - Order and Category both declare $guarded = [].
 *
 * These tests cover the two rules that replaced that: the acting user comes
 * from the token, and administrative endpoints are gated by role.
 */
class ApiAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $role, array $attributes = []): User
    {
        $user = User::factory()->create($attributes + ['email_verified_at' => now()]);
        $user->assignRoleByName($role);

        return $user->fresh();
    }

    private function service(?Category $category = null): Services
    {
        $category ??= Category::create(['name' => 'Plumbing '.uniqid()]);

        return Services::create([
            'title' => 'Leak repair',
            'price' => 1500,
            'category_id' => $category->id,
            'description' => 'x',
        ]);
    }

    private function availability(): int
    {
        return DB::table('availabilities')->insertGetId([
            'name' => 'Morning',
            'available_from' => '09:00',
            'available_to' => '12:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Role gates
    |--------------------------------------------------------------------------
    */

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function adminRouteProvider(): array
    {
        return [
            'list every order' => ['get', '/api/orders'],
            'edit an order' => ['put', '/api/orders/1'],
            'edit an order (app route)' => ['put', '/api/order_update/1'],
            'create a category' => ['post', '/api/category_add'],
            'rename a category' => ['put', '/api/category_update/1'],
        ];
    }

    /** @dataProvider adminRouteProvider */
    public function test_a_resident_is_refused_admin_endpoints(string $verb, string $uri): void
    {
        Sanctum::actingAs($this->userWithRole('resident'));

        $this->{$verb.'Json'}($uri)->assertForbidden();
    }

    /** @dataProvider adminRouteProvider */
    public function test_a_technician_is_refused_admin_endpoints(string $verb, string $uri): void
    {
        Sanctum::actingAs($this->userWithRole('technician'));

        $this->{$verb.'Json'}($uri)->assertForbidden();
    }

    public function test_an_admin_is_allowed_through(): void
    {
        Sanctum::actingAs($this->userWithRole('admin'));

        $this->getJson('/api/orders')->assertOk();
    }

    public function test_a_resident_is_refused_technician_endpoints(): void
    {
        Sanctum::actingAs($this->userWithRole('resident'));

        $this->getJson('/api/tech_orders')->assertForbidden();
        $this->putJson('/api/orders/1/status')->assertForbidden();
    }

    /*
    |--------------------------------------------------------------------------
    | The acting user comes from the token
    |--------------------------------------------------------------------------
    */

    public function test_the_wishlist_endpoint_returns_only_the_callers_own_rows(): void
    {
        $mine = $this->userWithRole('resident');
        $theirs = $this->userWithRole('resident');

        $myService = $this->service();
        $theirService = $this->service();

        DB::table('wishlists')->insert([
            ['user_id' => $mine->id, 'services_id' => $myService->id, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $theirs->id, 'services_id' => $theirService->id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Sanctum::actingAs($mine);

        $response = $this->getJson('/api/wishlists')->assertOk();

        $ids = array_column($response->json('data'), 'id');

        $this->assertContains($myService->id, $ids);
        $this->assertNotContains($theirService->id, $ids, 'Another user\'s wishlist row was returned.');
    }

    public function test_order_status_returns_only_the_callers_own_orders(): void
    {
        $mine = $this->userWithRole('resident');
        $theirs = $this->userWithRole('resident');

        Order::create(['user_id' => $mine->id, 'name' => 'Mine', 'status' => 'pending']);
        Order::create(['user_id' => $theirs->id, 'name' => 'Theirs', 'status' => 'pending']);

        Sanctum::actingAs($mine);

        $rows = $this->getJson('/api/order_status')->assertOk()->json();

        foreach ($rows as $row) {
            $this->assertSame($mine->id, $row['user_id'], 'Another user\'s order leaked.');
        }
    }

    /**
     * The body used to carry user_id, so an order could be filed against any
     * account. It is ignored now; ownership follows the token.
     */
    public function test_placing_an_order_ignores_a_user_id_in_the_body(): void
    {
        $me = $this->userWithRole('resident');
        $victim = $this->userWithRole('resident');

        $service = $this->service();
        $availability = $this->availability();

        Sanctum::actingAs($me);

        $this->postJson('/api/cart_add', [
            'user_id' => $victim->id,
            'name' => 'Someone',
            'email' => 'someone@example.test',
            'phone' => '123',
            'address' => 'Somewhere',
            'quantity' => 1,
            'service_id' => $service->id,
            'colors_id' => $availability,
        ])->assertOk();

        $this->assertDatabaseHas('orders', ['user_id' => $me->id]);
        $this->assertDatabaseMissing('orders', ['user_id' => $victim->id]);
    }

    public function test_removing_a_wishlist_row_cannot_touch_another_users(): void
    {
        $me = $this->userWithRole('resident');
        $victim = $this->userWithRole('resident');
        $service = $this->service();

        DB::table('wishlists')->insert([
            'user_id' => $victim->id,
            'services_id' => $service->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Sanctum::actingAs($me);

        $this->deleteJson('/api/wishlist_remove/'.$service->id)->assertOk();

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $victim->id,
            'services_id' => $service->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Technicians are confined to their own trade
    |--------------------------------------------------------------------------
    */

    public function test_a_technician_cannot_accept_a_job_outside_their_trade(): void
    {
        $plumbing = Category::create(['name' => 'Plumbing']);
        $electrical = Category::create(['name' => 'Electrical']);

        $plumber = $this->userWithRole('technician', ['category_id' => $plumbing->id]);

        $resident = $this->userWithRole('resident');
        $order = Order::create(['user_id' => $resident->id, 'name' => 'R', 'status' => 'shipped']);
        $order->items()->create([
            'service_id' => $this->service($electrical)->id,
            'availability_id' => $this->availability(),
            'category_id' => $electrical->id,
            'quantity' => 1,
        ]);

        Sanctum::actingAs($plumber);

        $this->putJson('/api/orders/'.$order->id.'/status')->assertForbidden();

        $this->assertSame('shipped', $order->fresh()->status);
    }

    /**
     * The API wrote 'accept' while the web portal wrote 'accepted', so a job
     * accepted from the phone never matched the dashboard's filters.
     */
    public function test_accepting_a_job_in_your_own_trade_writes_the_same_status_as_the_web_portal(): void
    {
        $plumbing = Category::create(['name' => 'Plumbing']);
        $plumber = $this->userWithRole('technician', ['category_id' => $plumbing->id]);

        $resident = $this->userWithRole('resident');
        $order = Order::create(['user_id' => $resident->id, 'name' => 'R', 'status' => 'shipped']);
        $order->items()->create([
            'service_id' => $this->service($plumbing)->id,
            'availability_id' => $this->availability(),
            'category_id' => $plumbing->id,
            'quantity' => 1,
        ]);

        Sanctum::actingAs($plumber);

        $this->putJson('/api/orders/'.$order->id.'/status')->assertOk();

        $this->assertSame('accepted', $order->fresh()->status);
    }

    /*
    |--------------------------------------------------------------------------
    | Column allowlists
    |--------------------------------------------------------------------------
    */

    /**
     * update(request()->all()) on a model with $guarded = [] let the caller
     * pick the columns, user_id and total included.
     */
    public function test_editing_an_order_cannot_reassign_it_or_rewrite_its_total(): void
    {
        $admin = $this->userWithRole('admin');
        $owner = $this->userWithRole('resident');
        $other = $this->userWithRole('resident');

        $order = Order::create([
            'user_id' => $owner->id,
            'name' => 'Original',
            'status' => 'pending',
            'total' => 5000,
        ]);

        Sanctum::actingAs($admin);

        $this->putJson('/api/order_update/'.$order->id, [
            'name' => 'Edited',
            'user_id' => $other->id,
            'total' => 0,
        ])->assertOk();

        $order->refresh();

        $this->assertSame('Edited', $order->name, 'The allowed column should still be written.');
        $this->assertSame($owner->id, $order->user_id, 'user_id was reassigned.');
        $this->assertEquals(5000, $order->total, 'total was rewritten.');
    }
}
