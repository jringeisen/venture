<?php

use App\Models\PromptAnswer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prompt_answers', function (Blueprint $table) {
            $table->integer('word_count')->default(0)->after('content');
        });

        PromptAnswer::all()->each(function (PromptAnswer $promptAnswer) {
            $promptAnswer->update([
                'word_count' => str_word_count($promptAnswer->content),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('prompt_answers', function (Blueprint $table) {
            $table->dropColumn('word_count');
        });
    }
};
