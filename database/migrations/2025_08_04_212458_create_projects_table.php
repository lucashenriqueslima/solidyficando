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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_category_id')
                ->constrained('project_categories')
                ->onDelete('cascade');
            $table->morphs('projectable');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['in_planning', 'planned', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('planned');
            $table->decimal('budget', 15, 2)->nullable();
            $table->decimal('spent', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
