<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_courses', function (Blueprint $table) {
            $table->index(['user_id', 'completed_at']);
        });

        Schema::table('compliance_reports', function (Blueprint $table) {
            $table->index(['parent_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('user_courses', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'completed_at']);
        });

        Schema::table('compliance_reports', function (Blueprint $table) {
            $table->dropIndex(['parent_id', 'created_at']);
        });
    }
};
