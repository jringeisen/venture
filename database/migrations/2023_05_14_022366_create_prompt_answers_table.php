<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prompt_question_id')->constrained()->cascadeOnDelete();
            $table->string('subject_category')->nullable();
            $table->text('content')->nullable();
            $table->text('questions')->nullable();
            $table->text('outline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_answers');
    }
};
