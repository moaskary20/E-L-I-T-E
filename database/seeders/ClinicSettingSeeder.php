<?php

namespace Database\Seeders;

use App\Models\ClinicSetting;
use Illuminate\Database\Seeder;

class ClinicSettingSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('clinic.default_hours') as $day => $hours) {
            ClinicSetting::updateOrCreate(
                ['day_of_week' => $day],
                [
                    'is_open' => $hours['is_open'],
                    'start_time' => $hours['is_open'] ? $hours['start'] : null,
                    'end_time' => $hours['is_open'] ? $hours['end'] : null,
                ]
            );
        }
    }
}
