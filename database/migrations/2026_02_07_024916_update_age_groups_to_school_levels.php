<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Map old granular age ranges to new school level ranges:
     * - 5-6, 7-8, 9-10 → 5-10 (Elementary)
     * - 11-12, 13-14 → 11-13 (Middle)
     * - 15-16, 17-19 → 14-18 (High)
     */
    public function up(): void
    {
        // Elementary: ages 5-10
        DB::table('courses')
            ->whereIn('min_age', [5, 7, 9])
            ->whereIn('max_age', [6, 8, 10])
            ->update(['min_age' => 5, 'max_age' => 10]);

        // Middle: ages 11-13
        DB::table('courses')
            ->whereIn('min_age', [11, 13])
            ->whereIn('max_age', [12, 14])
            ->update(['min_age' => 11, 'max_age' => 13]);

        // High: ages 14-18
        DB::table('courses')
            ->whereIn('min_age', [15, 17])
            ->whereIn('max_age', [16, 19])
            ->update(['min_age' => 14, 'max_age' => 18]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reliably reverse this mapping since multiple old ranges
        // mapped to a single new range. Data will remain at the new values.
    }
};
