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
        Schema::create('course_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_prompt_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->json('learning_objectives')->nullable();
            $table->json('trivia_questions')->nullable();
            $table->unsignedInteger('estimated_duration_minutes')->default(15);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_prompt_id', 'day_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_days');
    }
};
