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
            // Remove o relacionamento anterior
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');

            // Adiciona os campos morphs (company_type, company_id)
            $table->nullableMorphs('movementable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_movements', function (Blueprint $table) {
            // Remove morphs
            $table->dropMorphs('moventable');

            // Adiciona novamente o company_id
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }
};
