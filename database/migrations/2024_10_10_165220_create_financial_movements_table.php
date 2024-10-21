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
        Schema::create('financial_movements', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 10, 2);
            $table->date('payment_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('description')->nullable();
            $table->enum('status', ['paid', 'pending', 'canceled']);
            $table->enum('flow_type', ['in', 'out']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_movements', function (Blueprint $table) {
            $table->dropForeign(['financial_movement_type_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['person_id']);
        });
        Schema::dropIfExists('financial_movements');
    }
};
