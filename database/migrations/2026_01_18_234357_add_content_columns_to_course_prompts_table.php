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
        Schema::table('course_prompts', function (Blueprint $table) {
            $table->string('title')->after('week_number');
            $table->text('description')->nullable()->after('title');
            $table->text('prompt_text')->nullable()->after('description');
            $table->json('learning_objectives')->nullable()->after('prompt_text');
            $table->json('trivia_questions')->nullable()->after('learning_objectives');
            $table->json('additional_resources')->nullable()->after('trivia_questions');
            $table->integer('estimated_duration_minutes')->default(30)->after('additional_resources');

            $table->dropForeign(['prompt_id']);
            $table->dropColumn('prompt_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_prompts', function (Blueprint $table) {
            $table->foreignId('prompt_id')->nullable()->constrained()->cascadeOnDelete();

            $table->dropColumn([
                'title',
                'description',
                'prompt_text',
                'learning_objectives',
                'trivia_questions',
                'additional_resources',
                'estimated_duration_minutes',
            ]);
        });
    }
};
