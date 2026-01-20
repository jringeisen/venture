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
            $table->unsignedTinyInteger('days_count')->default(5)->after('week_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_prompts', function (Blueprint $table) {
            $table->dropColumn('days_count');
        });
    }
};
