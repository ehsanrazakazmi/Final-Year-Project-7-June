<?php

namespace Tests\Feature\Auth;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Routes that were reachable without logging in, and the email-verification
 * gate that was declared but never enforced on the portals.
 */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function guestRouteProvider(): array
    {
        return [
            'chat' => ['get', '/chat'],
            'messages index' => ['get', '/messages'],
            'messages store' => ['post', '/messages'],
        ];
    }

    /** @dataProvider guestRouteProvider */
    public function test_chat_routes_reject_guests(string $verb, string $uri): void
    {
        $this->{$verb}($uri)->assertRedirect('/login');
    }

    public function test_the_message_feed_does_not_leak_user_data_to_guests(): void
    {
        $user = User::factory()->create(['email' => 'private@example.test']);
        Message::create(['user_id' => $user->id, 'message' => 'hello']);

        $response = $this->get('/messages');

        $response->assertRedirect('/login');
        $this->assertStringNotContainsString('private@example.test', $response->getContent());
    }

    public function test_a_signed_in_user_can_still_read_the_message_feed(): void
    {
        $user = User::factory()->resident()->create();

        $this->actingAs($user)->get('/messages')->assertOk();
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function portalProvider(): array
    {
        return [
            'admin' => ['admin', '/adminpanel'],
            'resident' => ['resident', '/pages/home'],
            'technician' => ['technician', '/technicianpanel/introduction'],
        ];
    }

    /** @dataProvider portalProvider */
    public function test_an_unverified_user_cannot_reach_their_portal(string $role, string $uri): void
    {
        $user = User::factory()->{$role}()->unverified()->create();

        $this->actingAs($user)->get($uri)->assertRedirect(route('verification.notice'));
    }

    /** @dataProvider portalProvider */
    public function test_a_verified_user_can_reach_their_portal(string $role, string $uri): void
    {
        $user = User::factory()->{$role}()->create();

        $this->actingAs($user)->get($uri)->assertOk();
    }
}
