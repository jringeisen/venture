<?php

namespace App\Services;

use App\Models\ActiveTime;
use App\Models\User;
use Cache;

class StudentAttendanceService
{
    public function update(int $totalSeconds): void
    {
        $user = User::find(1); // TODO: Replace with user passed through request once Jonathon's MR finished.

        $date = date('Y-m-d');

        $cacheRecordExists = Cache::has("$user->id-$date");

        if (!$cacheRecordExists) {
            $this->putInCache($user, $totalSeconds);
        } else {
            $cacheRecord = Cache::get("$user->id-$date");

            $cacheRecordJson = json_decode($cacheRecord);

            if ($totalSeconds < $cacheRecordJson->totalSeconds) {
                // The time has been reset. So persist the last recorded total seconds and reset the cache
                // with the current value.
                $this->persist($cacheRecordJson->totalSeconds);
                $this->putInCache($user, $totalSeconds);
            } else {
                $this->updateCache($user, $totalSeconds, $cacheRecordJson->created);
            }
        }
    }

    private function putInCache(User $user, int $totalSeconds): void
    {
        $date = date('Y-m-d');
        $time = time();

        Cache::put(
            "$user->id-$date",
            json_encode(
                [
                    'totalSeconds' => $totalSeconds,
                    'created' => $time,
                    'lastUpdatedTime' => $time
                ]
            )
        );
    }

    public function persist(int $totalSeconds, string|null $date = null): void
    {
        $user = User::find(1); // TODO: Replace with user passed through request once Jonathon's MR finished.

        $date = $date ?? date('Y-m-d');

        $record = ActiveTime::where('user_id', $user->id)
            ->where('date', date('Y-m-d'))
            ->first();

        if ($record) {
            $record->update(['total_seconds' => $record->total_seconds + $totalSeconds]);
        } else {
            ActiveTime::insert(
                [
                    'user_id' => $user->id,
                    'date' => date('Y-m-d'),
                    'total_seconds' => $totalSeconds
                ]
            );
        }

        Cache::delete("$user->id-$date");
    }

    private function updateCache(User $user, int $totalSeconds, int $createdTime): void
    {
        $date = date('Y-m-d');
        $time = time();

        Cache::put(
            "$user->id-$date",
            json_encode(
                [
                    'totalSeconds' => $totalSeconds,
                    'created' => $createdTime,
                    'lastUpdatedTime' => $time
                ]
            )
        );
    }
}
