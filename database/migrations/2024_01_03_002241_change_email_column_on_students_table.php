<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('is_temporary_password');

            $table->renameColumn('email', 'username');

            $table->unique('username');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('is_temporary_password')->default(false);

            $table->dropUnique('students_username_unique');

            $table->renameColumn('username', 'email');
        });
    }
};
