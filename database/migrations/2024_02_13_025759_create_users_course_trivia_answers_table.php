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
        Schema::create('users_course_trivia_answers', static function (Blueprint $table) {
            $table->id();

            $table->foreignId('users_course_trivia_id')->constrained('users_course_trivia')->cascadeOnDelete();
            $table->foreignId('ctqa_id')->constrained('course_trivia_question_answers')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_course_trivia_answers');
    }
};
