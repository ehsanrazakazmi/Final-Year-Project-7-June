<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `category_id`, `latitude` and `longitude` were NOT NULL with no default, so
 * creating a user without them only succeeded because config/database.php sets
 * 'strict' => false and MySQL silently coerced them to 0. Admin-created users
 * supply none of these, so make them genuinely optional.
 *
 * Raw SQL is used because ->change() would require doctrine/dbal.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `users` MODIFY `category_id` INT NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `users` MODIFY `latitude` DOUBLE NULL DEFAULT NULL');
        DB::statement('ALTER TABLE `users` MODIFY `longitude` DOUBLE NULL DEFAULT NULL');
    }

    public function down(): void
    {
        DB::statement('UPDATE `users` SET `category_id` = 0 WHERE `category_id` IS NULL');
        DB::statement('UPDATE `users` SET `latitude` = 0 WHERE `latitude` IS NULL');
        DB::statement('UPDATE `users` SET `longitude` = 0 WHERE `longitude` IS NULL');

        DB::statement('ALTER TABLE `users` MODIFY `category_id` INT NOT NULL');
        DB::statement('ALTER TABLE `users` MODIFY `latitude` DOUBLE NOT NULL');
        DB::statement('ALTER TABLE `users` MODIFY `longitude` DOUBLE NOT NULL');
    }
};
