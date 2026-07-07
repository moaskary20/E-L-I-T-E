<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkingHoursController extends Controller
{
    private array $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    public function index(): View
    {
        $settings = ClinicSetting::all()->keyBy('day_of_week');
        $days = collect($this->days)->map(function ($day) use ($settings) {
            $setting = $settings->get($day);
            $defaults = config("clinic.default_hours.$day");

            return [
                'day_of_week' => $day,
                'is_open' => $setting?->is_open ?? $defaults['is_open'] ?? false,
                'start_time' => $setting?->start_time
                    ? substr((string) $setting->start_time, 0, 5)
                    : ($defaults['start'] ?? null),
                'end_time' => $setting?->end_time
                    ? substr((string) $setting->end_time, 0, 5)
                    : ($defaults['end'] ?? null),
            ];
        })->values();

        $defaultDays = collect($this->days)->map(function ($day) {
            $defaults = config("clinic.default_hours.$day");

            return [
                'day_of_week' => $day,
                'is_open' => $defaults['is_open'] ?? false,
                'start_time' => $defaults['start'] ?? null,
                'end_time' => $defaults['end'] ?? null,
            ];
        })->values();

        return view('admin.working-hours.index', compact('days', 'defaultDays'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'days' => 'required|array',
            'days.*.day_of_week' => 'required|string',
            'days.*.is_open' => 'required|boolean',
            'days.*.start_time' => 'nullable',
            'days.*.end_time' => 'nullable',
        ]);

        foreach ($validated['days'] as $day) {
            ClinicSetting::updateOrCreate(
                ['day_of_week' => $day['day_of_week']],
                [
                    'is_open' => $day['is_open'],
                    'start_time' => $day['is_open'] ? ($day['start_time'] ?? '09:00') : null,
                    'end_time' => $day['is_open'] ? ($day['end_time'] ?? '17:00') : null,
                ]
            );
        }

        return back()->with('success', 'Working hours saved successfully.');
    }
}
