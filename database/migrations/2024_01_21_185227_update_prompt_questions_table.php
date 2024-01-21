<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prompt_questions', static function (Blueprint $table) {
            $table->dropForeign('prompt_questions_student_id_foreign');
            $table->dropColumn('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prompt_questions', static function (Blueprint $table) {
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
        });
    }
};
