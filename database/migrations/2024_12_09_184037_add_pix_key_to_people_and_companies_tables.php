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
        Schema::table('people', function (Blueprint $table) {
            $table->string('pix_key')->nullable()->after('cpf');
            $table->enum('pix_key_type', ['cpf', 'cnpj', 'phone', 'email', 'other'])->nullable()->after('pix_key')->unique();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('pix_key')->nullable()->after('cnpj');
            $table->enum('pix_key_type', ['cpf', 'cnpj', 'phone', 'email', 'other'])->nullable()->after('pix_key')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn('pix_key');
            $table->dropColumn('pix_key_type');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('pix_key');
            $table->dropColumn('pix_key_type');
        });
    }
};
