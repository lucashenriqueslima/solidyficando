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
        Schema::table('financial_movement_categories', function (Blueprint $table) {
            DB::table('financial_movement_categories')->insert([
                'name' => 'Contribuição Avulsa (Boleto)',
                'flow_type' => 'in',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_movement_categories', function (Blueprint $table) {
            DB::table('financial_movement_categories')
                ->where(
                    'name',
                    'Contribuição Avulsa (Boleto)',
                )
                ->delete();
        });
    }
};
