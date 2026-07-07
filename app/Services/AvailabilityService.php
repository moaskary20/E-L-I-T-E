<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\BlockedSlot;
use App\Models\ClinicSetting;
use Carbon\Carbon;

class AvailabilityService
{
    public function getBlockedDates(string $fromDate, string $toDate): array
    {
        $from = Carbon::parse($fromDate);
        $to = Carbon::parse($toDate);
        $dates = collect();

        $periods = \App\Models\BlockedPeriod::where('start_date', '<=', $to)
            ->where('end_date', '>=', $from)
            ->get();

        foreach ($periods as $period) {
            $current = $period->start_date->copy();
            while ($current->lte($period->end_date)) {
                if ($current->between($from, $to)) {
                    $dates->push($current->format('Y-m-d'));
                }
                $current->addDay();
            }
        }

        return $dates->unique()->values()->map(fn ($d) => ['blocked_date' => $d])->all();
    }

    public function getUnavailableSlots(string $bookingDate): array
    {
        $slots = collect();

        $appointments = Appointment::where('date', $bookingDate)
            ->whereNotIn('status', ['cancelled'])
            ->get(['start_time', 'end_time']);

        foreach ($appointments as $apt) {
            $slots->push([
                'start_time' => substr((string) $apt->start_time, 0, 8),
                'end_time' => substr((string) $apt->end_time, 0, 8),
                'reason' => 'booked',
            ]);
        }

        $blocked = BlockedSlot::where('date', $bookingDate)->get();
        foreach ($blocked as $slot) {
            $slots->push([
                'start_time' => substr((string) $slot->start_time, 0, 8),
                'end_time' => substr((string) $slot->end_time, 0, 8),
                'reason' => $slot->reason ?? 'blocked',
            ]);
        }

        return $slots->all();
    }

    public function generateTimeSlots(string $date): array
    {
        $dayName = Carbon::parse($date)->format('l');
        $hours = ClinicSetting::hoursMap()[$dayName] ?? null;

        if (! $hours) {
            return [];
        }

        $duration = config('clinic.slot_duration_minutes', 30);
        $slots = [];
        $startMin = $this->timeToMinutes($hours['start']);
        $endMin = $this->timeToMinutes($hours['end']);

        for ($min = $startMin; $min + $duration <= $endMin; $min += $duration) {
            $slots[] = [
                'startTime' => $this->minutesToTime($min),
                'endTime' => $this->minutesToTime($min + $duration),
                'available' => true,
            ];
        }

        return $slots;
    }

    private function timeToMinutes(string $time): int
    {
        [$h, $m] = array_map('intval', explode(':', $time));

        return $h * 60 + $m;
    }

    private function minutesToTime(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }
}
