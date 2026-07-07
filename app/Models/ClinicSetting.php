<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ClinicSetting extends Model
{
    use HasUuids;

    protected $fillable = ['day_of_week', 'is_open', 'start_time', 'end_time'];

    protected function casts(): array
    {
        return [
            'is_open' => 'boolean',
        ];
    }

    public static function ensureDefaults(): void
    {
        if (self::count() > 0) {
            return;
        }

        foreach (config('clinic.default_hours') as $day => $hours) {
            self::create([
                'day_of_week' => $day,
                'is_open' => $hours['is_open'],
                'start_time' => $hours['is_open'] ? $hours['start'] : null,
                'end_time' => $hours['is_open'] ? $hours['end'] : null,
            ]);
        }
    }

    public static function defaultHoursMap(): array
    {
        $map = [];

        foreach (config('clinic.default_hours') as $day => $hours) {
            if (! ($hours['is_open'] ?? false)) {
                $map[$day] = null;

                continue;
            }

            $map[$day] = [
                'start' => $hours['start'],
                'end' => $hours['end'],
            ];
        }

        return $map;
    }

    public static function hoursMap(): array
    {
        $order = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $settings = self::all()->keyBy('day_of_week');
        $defaults = self::defaultHoursMap();
        $map = [];

        foreach ($order as $day) {
            $setting = $settings->get($day);

            if (! $setting) {
                $map[$day] = $defaults[$day] ?? null;

                continue;
            }

            if (! $setting->is_open) {
                $map[$day] = null;

                continue;
            }

            $map[$day] = [
                'start' => substr((string) $setting->start_time, 0, 5),
                'end' => substr((string) $setting->end_time, 0, 5),
            ];
        }

        return $map;
    }
}
