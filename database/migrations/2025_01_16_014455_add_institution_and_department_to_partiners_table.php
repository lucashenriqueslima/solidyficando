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
        Schema::table('partiners', function (Blueprint $table) {
            $table->foreignId('institution_id')->nullable()->constrained('institutions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partiners', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['institution_id', 'department_id']);
        });
    }
};
