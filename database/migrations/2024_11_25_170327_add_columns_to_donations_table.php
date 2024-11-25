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
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('donation_category_id')->constrained();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('donation_category_id');
            $table->dropConstrainedForeignId('company_id');
            $table->dropColumn('description');
            $table->dropColumn('quantity');
        });
    }
};
