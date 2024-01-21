<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('email')->nullable()->change();
            $table->integer('grade')->nullable()->after('email');
            $table->integer('age')->nullable()->after('grade');
            $table->timestamp('motivational_message')->nullable()->after('age');
            $table->integer('current_streak')->default(0)->after('motivational_message');

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'username', 'grade', 'age', 'motivational_message', 'current_streak']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
