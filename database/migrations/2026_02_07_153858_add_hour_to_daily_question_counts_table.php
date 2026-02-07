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
        Schema::table('daily_question_counts', function (Blueprint $table) {
            if (! Schema::hasColumn('daily_question_counts', 'hour')) {
                $table->tinyInteger('hour')->unsigned()->default(0)->after('date');
            }
        });

        Schema::table('daily_question_counts', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropUnique(['parent_id', 'date']);
            $table->foreign('parent_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['user_id', 'date', 'hour']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_question_counts', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'date', 'hour']);
            $table->unique(['parent_id', 'date']);

            $table->dropColumn('hour');
        });
    }
};
