<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('partiners', function (Blueprint $table) {
            $table->string('asaas_id')->nullable()->unique();
            $table->boolean('is_to_charge')->default(false);
            $table->integer('billing_day')->nullable()->after('is_to_charge');

            DB::table('financial_movement_categories')->insert([
                'name' => 'Contribuição Mensal (Boleto)',
                'flow_type' => 'in',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partiners', function (Blueprint $table) {
            $table->dropUnique(['asaas_id']);
            $table->dropColumn(['asaas_id', 'is_to_charge']);
            $table->dropColumn('billing_day');
        });
    }
};
