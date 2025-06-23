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
        Schema::create('recruit_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruit_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('institution');
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->integer('sort');
            $table->enum('status', [
                'completed',
                'in_progress',
                'dropped_out'
            ])->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruit_courses');
    }
};
