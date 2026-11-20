<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('clients', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('clients', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('clients', 'estimated_students')) {
                $table->integer('estimated_students')->default(0);
            }
            if (!Schema::hasColumn('clients', 'active_modules')) {
                $table->json('active_modules')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $columns = array_filter(
                ['status', 'address', 'city', 'estimated_students', 'active_modules'],
                fn ($col) => Schema::hasColumn('clients', $col)
            );

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
