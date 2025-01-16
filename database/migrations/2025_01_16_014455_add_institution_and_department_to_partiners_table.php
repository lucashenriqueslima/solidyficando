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
            $table->foreignId('institution_id')->constrained('institutions')->nullable();
            $table->foreignId('department_id')->constrained('departments')->nullable();
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
