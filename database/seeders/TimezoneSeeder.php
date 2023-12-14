<?php

namespace Database\Seeders;

use App\Models\Timezone;
use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    public function run()
    {
        $data = json_decode(file_get_contents(asset('/assets/timezones.json')), true);

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
