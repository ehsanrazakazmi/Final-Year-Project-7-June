<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The "colors" CRUD was copied from a tutorial and then repurposed as the
 * technician availability feature, but nothing was renamed: `colors` held
 * availability windows, and `code`/`code1` held the From/To times.
 *
 * Renames (data preserved - RENAME TABLE / CHANGE COLUMN do not copy rows):
 *   colors           -> availabilities
 *   color_services   -> availability_services
 *   colors.code      -> available_from
 *   colors.code1     -> available_to
 *   *.color_id       -> availability_id   (pivot and items)
 *
 * Every step is guarded, so this is a no-op on a database migrated fresh from
 * the updated create_* migrations.
 */
return new class extends Migration
{
    public function up(): void
    {
        // RENAME TABLE / CHANGE COLUMN are MySQL syntax. A database created from
        // the updated create_* migrations already has the new names.
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('colors') && ! Schema::hasTable('availabilities')) {
            DB::statement('RENAME TABLE `colors` TO `availabilities`');
        }

        if (Schema::hasTable('color_services') && ! Schema::hasTable('availability_services')) {
            DB::statement('RENAME TABLE `color_services` TO `availability_services`');
        }

        if (Schema::hasColumn('availabilities', 'code')) {
            DB::statement('ALTER TABLE `availabilities` CHANGE `code` `available_from` VARCHAR(255) NOT NULL');
        }

        if (Schema::hasColumn('availabilities', 'code1')) {
            DB::statement('ALTER TABLE `availabilities` CHANGE `code1` `available_to` VARCHAR(255) NOT NULL');
        }

        if (Schema::hasColumn('availability_services', 'color_id')) {
            DB::statement('ALTER TABLE `availability_services` CHANGE `color_id` `availability_id` INT UNSIGNED NOT NULL');
        }

        if (Schema::hasColumn('items', 'color_id')) {
            DB::statement('ALTER TABLE `items` CHANGE `color_id` `availability_id` BIGINT UNSIGNED NOT NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasColumn('items', 'availability_id')) {
            DB::statement('ALTER TABLE `items` CHANGE `availability_id` `color_id` BIGINT UNSIGNED NOT NULL');
        }

        if (Schema::hasColumn('availability_services', 'availability_id')) {
            DB::statement('ALTER TABLE `availability_services` CHANGE `availability_id` `color_id` INT UNSIGNED NOT NULL');
        }

        if (Schema::hasColumn('availabilities', 'available_to')) {
            DB::statement('ALTER TABLE `availabilities` CHANGE `available_to` `code1` VARCHAR(255) NOT NULL');
        }

        if (Schema::hasColumn('availabilities', 'available_from')) {
            DB::statement('ALTER TABLE `availabilities` CHANGE `available_from` `code` VARCHAR(255) NOT NULL');
        }

        if (Schema::hasTable('availability_services') && ! Schema::hasTable('color_services')) {
            DB::statement('RENAME TABLE `availability_services` TO `color_services`');
        }

        if (Schema::hasTable('availabilities') && ! Schema::hasTable('colors')) {
            DB::statement('RENAME TABLE `availabilities` TO `colors`');
        }
    }
};
