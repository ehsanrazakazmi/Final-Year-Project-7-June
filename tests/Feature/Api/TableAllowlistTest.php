<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * /api/tables/{table} used to run DB::table($table) on whatever the caller
 * supplied, unauthenticated - so /api/tables/users returned every password
 * hash. It is now restricted to an allowlist of catalogue tables.
 */
class TableAllowlistTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The endpoint now sits behind auth:sanctum, so these tests authenticate
     * first. Being logged in must not be enough to read a table that is not on
     * the allowlist - that is the point of the allowlist, and what the blocked
     * cases below assert.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function blockedTableProvider(): array
    {
        return [
            'users' => ['users'],
            // Per-user data: returning the whole table handed every user's
            // wishlist to anyone who asked, so it left the allowlist.
            'wishlists' => ['wishlists'],
            'password_resets' => ['password_resets'],
            'personal_access_tokens' => ['personal_access_tokens'],
            'sessions' => ['sessions'],
            'migrations' => ['migrations'],
            'roles' => ['roles'],
        ];
    }

    /** @dataProvider blockedTableProvider */
    public function test_sensitive_tables_are_not_reachable(string $table): void
    {
        $this->getJson("/api/tables/{$table}")->assertNotFound();
    }

    public function test_no_password_hash_can_be_retrieved(): void
    {
        User::factory()->create(['password' => bcrypt('hunter2')]);

        $response = $this->get('/api/tables/users');

        $response->assertNotFound();
        $this->assertStringNotContainsString('$2y$', $response->getContent());
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function allowedTableProvider(): array
    {
        return [
            'categories' => ['categories'],
            'availabilities' => ['availabilities'],
            'services' => ['services'],
        ];
    }

    /**
     * The React Native app calls /api/tables/wishlists, so the allowed tables
     * must keep returning the same {"data": [...]} envelope.
     *
     * @dataProvider allowedTableProvider
     */
    public function test_catalogue_tables_remain_available(string $table): void
    {
        $this->getJson("/api/tables/{$table}")
            ->assertOk()
            ->assertJsonStructure(['data']);
    }
}
