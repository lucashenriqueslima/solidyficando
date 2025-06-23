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
            $table->string('invoice_url')->nullable();
            $table->string('bank_slip_url')->nullable();
            $table->string('asaas_id')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_movements', function (Blueprint $table) {
            $table->dropColumn('invoice_url');
            $table->dropColumn('bank_slip_url');
            $table->dropUnique(['asaas_id']);
        });
    }
};
