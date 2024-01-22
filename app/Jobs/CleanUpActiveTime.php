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
use JsonException;

class CleanUpActiveTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private StudentAttendanceService $studentAttendanceService;

    /**
     * Execute the job.
     * @throws JsonException
     */
    public function handle(StudentAttendanceService $studentAttendanceService): void
    {
        $this->studentAttendanceService = $studentAttendanceService;

        $students = User::whereNotNull('parent_id')->get();

        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        foreach ($students as $student) {
            $date = Carbon::now()
                ->setTimezone($student->timezone)
                ->format('Y-m-d');

            $this->persistActiveTime($student, $date);
            $this->persistActiveTime($student, $yesterday);
        }
    }

    /**
     * @throws JsonException
     */
    private function persistActiveTime(User $student, string $date): void
    {
        $now = time();

        $cacheRecord = Cache::get("$student->id-$date");

        if ($cacheRecord) {
            $cacheRecordJson = json_decode($cacheRecord, false, 512, JSON_THROW_ON_ERROR);

            $lastUpdatedUnixTime = $cacheRecordJson->lastUpdatedTime;

            if (($now - $lastUpdatedUnixTime) > 60) {
                $this->studentAttendanceService->persist($student, $cacheRecordJson->totalSeconds, $date);
            }
        }
    }
}
