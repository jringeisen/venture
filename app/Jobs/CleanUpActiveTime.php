<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\StudentAttendanceService;
use Cache;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanUpActiveTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TODO: Refactor to only students after Jonathon's code merged
        $students = User::all();

        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        foreach ($students as $student) {
            $date = date('Y-m-d');

            $this->persistActiveTime($student, $date);
            $this->persistActiveTime($student, $yesterday);
        }
    }

    private function persistActiveTime(User $student, string $date): void
    {
        $now = time();

        $cacheRecord = Cache::get("$student->id-$date");

        if ($cacheRecord) {
            $cacheRecordJson = json_decode($cacheRecord);

            $lastUpdatedUnixTime = $cacheRecordJson->lastUpdatedTime;

            if (($now - $lastUpdatedUnixTime) > 60) {
                (new StudentAttendanceService())->persist($cacheRecordJson->totalSeconds, $date);
            }
        }
    }
}
