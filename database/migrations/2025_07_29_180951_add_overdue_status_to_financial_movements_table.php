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
        Schema::table('financial_movements', function (Blueprint $table) {
            $table->enum('status', ['paid', 'pending', 'canceled', 'overdue'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_movements', function (Blueprint $table) {
            $table->enum('status', ['paid', 'pending', 'canceled'])->change();
        });
    }
};
