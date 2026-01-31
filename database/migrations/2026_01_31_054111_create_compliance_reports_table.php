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
        Schema::create('compliance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('state', 2)->default('FL');
            $table->string('title');
            $table->date('period_start');
            $table->date('period_end');
            $table->json('summary_statistics');
            $table->json('report_metadata')->nullable();
            $table->string('status')->default('generated');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['parent_id', 'student_id']);
            $table->index(['student_id', 'period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_reports');
    }
};
