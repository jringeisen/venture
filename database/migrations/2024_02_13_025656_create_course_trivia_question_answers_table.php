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
        Schema::create('course_trivia_question_answers', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_trivia_id')->constrained('course_trivia')->cascadeOnDelete();

            $table->string('answer');
            $table->boolean('correct')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_trivia_question_answers');
    }
};
