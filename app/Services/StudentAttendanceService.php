<?php

namespace App\Services;

use App\Models\ActiveTime;
use App\Models\Student;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use JsonException;

class StudentAttendanceService
{
    /**
     * @throws JsonException
     */
    public function update(User|Student $user, int $totalSeconds): void
    {
        $date = Carbon::now()
            ->setTimezone($user->timezone)
            ->format('Y-m-d');

        $cacheRecordExists = Cache::has("$user->id-$date");

        if (! $cacheRecordExists) {
            $this->putInCache($user, $totalSeconds);
        } else {
            $cacheRecord = Cache::get("$user->id-$date");

            $cacheRecordJson = json_decode($cacheRecord, false, 512, JSON_THROW_ON_ERROR);

            if ($totalSeconds < $cacheRecordJson->totalSeconds) {
                // The time has been reset. So persist the last recorded total seconds and reset the cache
                // with the current value.
                $this->persist($user, $cacheRecordJson->totalSeconds);
                $this->putInCache($user, $totalSeconds);
            } else {
                $this->updateCache($user, $totalSeconds, $cacheRecordJson->created);
            }
        }
    }

    /**
     * @throws JsonException
     */
    private function putInCache(User|Student $user, int $totalSeconds): void
    {
        $date = Carbon::now()
            ->setTimezone($user->timezone)
            ->format('Y-m-d');
        $time = time();

        Cache::put(
            "$user->id-$date",
            json_encode([
                'totalSeconds' => $totalSeconds,
                'created' => $time,
                'lastUpdatedTime' => $time,
            ], JSON_THROW_ON_ERROR)
        );
    }

    public function persist(User|Student $user, int $totalSeconds, ?string $date = null): void
    {
        $date = $date ?? Carbon::now()
            ->setTimezone($user->timezone)
            ->format('Y-m-d');

        $record = ActiveTime::where('user_id', $user->id)
            ->where('date', $date)
            ->first();

        if ($record) {
            $record->update(['total_seconds' => $record->total_seconds + $totalSeconds]);
        } else {
            $now = Carbon::now();

            ActiveTime::insert(
                [
                    'user_id' => $user->id,
                    'date' => $date,
                    'total_seconds' => $totalSeconds,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        Cache::delete("$user->id-$date");
    }

    /**
     * @throws JsonException
     */
    private function updateCache(User|Student $user, int $totalSeconds, int $createdTime): void
    {
        $date = Carbon::now()
            ->setTimezone($user->timezone)
            ->format('Y-m-d');
        $time = time();

        Cache::put(
            "$user->id-$date",
            json_encode([
                'totalSeconds' => $totalSeconds,
                'created' => $createdTime,
                'lastUpdatedTime' => $time,
            ], JSON_THROW_ON_ERROR)
        );
    }
}
