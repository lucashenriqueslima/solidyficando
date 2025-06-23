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
        Schema::create('recruit_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruit_id')->constrained()->onDelete('cascade');
            $table->string('job_title');
            $table->string('company_name');
            $table->enum('type', [
                'full_time',
                'part_time',
                'freelancer',
                'internship',
                'apprentice',
                'trainee'
            ])->nullable();
            $table->integer('sort');
            $table->boolean('is_current');
            $table->text('description')->nullable();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruit_jobs');
    }
};
