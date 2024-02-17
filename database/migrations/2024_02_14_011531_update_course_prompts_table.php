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
        Schema::table('course_prompts', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('prompt_id');
            $table->text('prompt')->after('course_id');
            $table->integer('weight')->default(0)->after('week_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_prompts', static function (Blueprint $table) {
            $table->foreignId('prompt_id')->constrained();
            $table->dropColumn('prompt');
            $table->dropColumn('weight');
        });
    }
};
