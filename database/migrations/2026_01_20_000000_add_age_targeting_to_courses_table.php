<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedTinyInteger('min_age')->nullable()->after('length_in_weeks');
            $table->unsignedTinyInteger('max_age')->nullable()->after('min_age');

            $table->index(['min_age', 'max_age']);
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['min_age', 'max_age']);
            $table->dropColumn(['min_age', 'max_age']);
        });
    }
};
