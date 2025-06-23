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
        Schema::table('pix_key_type', function (Blueprint $table) {
            Schema::table('people', function (Blueprint $table) {
                $table->unique('pix_key');
                $table->dropUnique(['pix_key_type']);
            });

            Schema::table('companies', function (Blueprint $table) {
                $table->unique('pix_key');
                $table->dropUnique(['pix_key_type']);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pix_key_type', function (Blueprint $table) {
            Schema::table('people', function (Blueprint $table) {
                $table->unique('pix_key_type');
            });

            Schema::table('companies', function (Blueprint $table) {
                $table->unique('pix_key_type');
            });
        });
    }
};
