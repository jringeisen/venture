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
        Schema::table('courses', static function (Blueprint $table) {
            $table->foreignId('categories_grade_id')->after('length_in_weeks')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_grade_id');
        });
    }
};
