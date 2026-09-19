<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Category;
use App\Models\Order;
use App\Models\Services;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * The `items` table used to contradict every caller: the migration declared
 * product_id, CheckoutController wrote service_id + category_id, and
 * TechnicianController filtered on category_id. Checkout and the technician
 * dashboard both failed with "Unknown column".
 */
class OrderItemSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_items_has_the_columns_its_callers_write(): void
    {
        foreach (['service_id', 'category_id', 'availability_id', 'order_id', 'quantity'] as $column) {
            $this->assertTrue(Schema::hasColumn('items', $column), "items.{$column} is missing");
        }

        $this->assertFalse(Schema::hasColumn('items', 'product_id'), 'stale product_id is back');
    }

    public function test_an_order_item_can_be_created_the_way_checkout_does(): void
    {
        $category = Category::create(['name' => 'Plumbing']);
        $service = Services::create([
            'title' => 'Leak repair', 'category_id' => $category->id,
            'price' => 1500, 'description' => 'x', 'image' => 'products/x.png',
        ]);
        $availability = Availability::create([
            'name' => 'subah', 'available_from' => '05:00', 'available_to' => '09:00',
        ]);
        $order = Order::create([
            'user_id' => User::factory()->resident()->create()->id,
            'name' => 'Buyer', 'email' => 'b@example.test',
            'phone' => '000', 'address' => 'somewhere', 'status' => 'pending',
        ]);

        $item = $order->items()->create([
            'service_id' => $service->id,
            'availability_id' => $availability->id,
            'category_id' => $category->id,
            'quantity' => 2,
        ]);

        $this->assertSame($service->id, $item->Services->id);
        $this->assertSame($availability->id, $item->availability->id);
        $this->assertSame($order->id, $item->order->id);
    }

    public function test_the_technician_dashboard_query_is_valid_sql(): void
    {
        // Regression guard for "Unknown column 'i.category_id'".
        $technician = User::factory()->technician()->create();
        $technician->forceFill(['category_id' => 1])->save();

        $this->actingAs($technician)->get('/technicianpanel')->assertOk();
    }
}
