<?php

namespace Database\Seeders;

use App\Models\Timezone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TimezoneSeeder extends Seeder
{
    public function run()
    {
        $data = Storage::disk('public')->json('timezones.json');

        Timezone::truncate();

        foreach ($data as $tzs) {
            foreach ($tzs as $timezones) {
                foreach ($timezones as $timezone) {
                    Timezone::create([
                        'label' => $timezone['label'],
                        'value' => $timezone['value'],
                    ]);
                }
            }
        }
    }
}
