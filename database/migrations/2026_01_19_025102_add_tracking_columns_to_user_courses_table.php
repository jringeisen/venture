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
        Schema::table('user_courses', function (Blueprint $table) {
            $table->timestamp('last_accessed_at')->nullable()->after('completed_at');
            $table->unsignedInteger('time_spent_minutes')->default(0)->after('last_accessed_at');
            $table->json('trivia_scores')->nullable()->after('time_spent_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_courses', function (Blueprint $table) {
            $table->dropColumn(['last_accessed_at', 'time_spent_minutes', 'trivia_scores']);
        });
    }
};
