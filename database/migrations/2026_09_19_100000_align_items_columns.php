<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The `items` table disagreed with every piece of code that touched it.
 *
 *   migration            product_id / availability_id / order_id / quantity
 *   CheckoutController   service_id, category_id, availability_id, quantity
 *   TechnicianController WHERE i.category_id = ?
 *   routes/api.php       INSERT ... (order_id, service_id, availability_id, ...)
 *
 * `product_id` was referenced nowhere outside the migration - a leftover from
 * the same tutorial the colours CRUD came from. Checkout, the technician
 * dashboard and /api/tech_orders all failed with "Unknown column".
 *
 * This settles on the names the application actually uses:
 *   product_id -> service_id
 *   + category_id   (denormalised from services.category_id; CartController
 *                    already copies it into the session cart, and the
 *                    technician dashboard filters shipped orders by it)
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('items', 'product_id') && ! Schema::hasColumn('items', 'service_id')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE `items` CHANGE `product_id` `service_id` BIGINT UNSIGNED NOT NULL');
            } else {
                Schema::table('items', fn (Blueprint $t) => $t->renameColumn('product_id', 'service_id'));
            }
        }

        if (! Schema::hasColumn('items', 'category_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->after('service_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('items', 'category_id')) {
            Schema::table('items', fn (Blueprint $t) => $t->dropColumn('category_id'));
        }

        if (Schema::hasColumn('items', 'service_id') && ! Schema::hasColumn('items', 'product_id')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE `items` CHANGE `service_id` `product_id` BIGINT UNSIGNED NOT NULL');
            } else {
                Schema::table('items', fn (Blueprint $t) => $t->renameColumn('service_id', 'product_id'));
            }
        }
    }
};
