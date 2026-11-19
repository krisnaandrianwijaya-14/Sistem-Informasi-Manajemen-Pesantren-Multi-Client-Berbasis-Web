<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_cards', function (Blueprint $t) {
    $t->id();
    $t->unsignedBigInteger('tenant_id')->index();
    $t->unsignedBigInteger('student_id')->index();
    $t->string('token', 64)->unique();
    $t->string('status', 20)->default('active');   // active | blocked | lost
    $t->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_cards');
    }
};
