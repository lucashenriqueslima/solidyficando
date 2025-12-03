<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('financial_movement_categories')->insert([
            'name' => 'Contribuição Avulsa (PIX)',
            'flow_type' => 'in',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('financial_movement_categories')
            ->where('name', 'Contribuição Avulsa (PIX)')
            ->where('flow_type', 'in')
            ->delete();
    }
};
