<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * `category_id`, `latitude` and `longitude` were NOT NULL with no default, so
 * creating a user without them only succeeded because config/database.php sets
 * 'strict' => false and MySQL silently coerced them to 0. Admin-created users
 * supply none of these, so make them genuinely optional.
 *
 * Each column is checked first: on an existing database they are present, but
 * on a freshly migrated one they are not, because the migrations that
 * originally added them are no longer in the repository. Guarding keeps
 * `migrate:fresh` working in both cases.
 *
 * Raw SQL is used because ->change() would require doctrine/dbal.
 */
return new class extends Migration
{
    /**
     * @var array<string, string>
     */
    private array $columns = [
        'category_id' => 'INT',
        'latitude' => 'DOUBLE',
        'longitude' => 'DOUBLE',
    ];

    public function up(): void
    {
        foreach ($this->columns as $column => $type) {
            if (Schema::hasColumn('users', $column)) {
                DB::statement("ALTER TABLE `users` MODIFY `{$column}` {$type} NULL DEFAULT NULL");
            }
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $column => $type) {
            if (! Schema::hasColumn('users', $column)) {
                continue;
            }

            DB::statement("UPDATE `users` SET `{$column}` = 0 WHERE `{$column}` IS NULL");
            DB::statement("ALTER TABLE `users` MODIFY `{$column}` {$type} NOT NULL");
        }
    }
};
