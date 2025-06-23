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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('cpf');
            $table->string('phone');
            $table->date('birthday');
            $table->decimal('family_income', 10, 2);
            $table->enum('education', ['preschool', 'high_school', 'higher_education']);
            $table->integer('children');
            $table->string('address');
            $table->string('number');
            $table->string('cep');
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->enum('housing', ['owned', 'rented']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
        });
        Schema::dropIfExists('people');
    }
};
