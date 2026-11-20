<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Make `client_id` column on `students` nullable.
 * Tests insert students without specifying a client, so allowing NULL avoids
 * foreign key violations. The foreign key constraint remains but permits NULL.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `students` MODIFY `client_id` BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Revert to NOT NULL (default behavior) – assumes a client with ID 1 exists.
        DB::statement('ALTER TABLE `students` MODIFY `client_id` BIGINT UNSIGNED NOT NULL');
    }
};
