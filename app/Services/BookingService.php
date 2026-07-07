<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\BlockedPeriod;
use App\Models\BlockedSlot;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingService
{
    public function book(array $data): array
    {
        $date = $data['date'];
        $startTime = strlen($data['start_time']) === 5 ? $data['start_time'].':00' : $data['start_time'];
        $duration = config('clinic.slot_duration_minutes', 30);
        $endTime = Carbon::createFromFormat('H:i:s', $startTime)->addMinutes($duration)->format('H:i:s');

        if ($this->isDateBlocked($date)) {
            return ['success' => false, 'error' => 'The clinic is closed on this date.'];
        }

        if (BlockedSlot::where('date', $date)->where('start_time', $startTime)->exists()) {
            return ['success' => false, 'error' => 'This time slot is not available.'];
        }

        if (Appointment::where('date', $date)->where('start_time', $startTime)->active()->exists()) {
            return ['success' => false, 'error' => 'This time slot was just booked by someone else. Please select another time.'];
        }

        $id = (string) Str::uuid();
        $reference = strtoupper(substr(str_replace('-', '', $id), 0, 8));

        try {
            $appointment = Appointment::create([
                'id' => $id,
                'booking_reference' => $reference,
                'patient_name' => $data['patient_name'],
                'patient_phone' => $data['patient_phone'],
                'patient_email' => $data['patient_email'],
                'condition_slug' => $data['condition_slug'],
                'condition_title' => $data['condition_title'],
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'confirmed',
            ]);
        } catch (\Throwable) {
            return ['success' => false, 'error' => 'This time slot was just booked by someone else. Please select another time.'];
        }

        return [
            'success' => true,
            'data' => [
                'id' => $appointment->id,
                'bookingReference' => $appointment->booking_reference,
                'patientName' => $appointment->patient_name,
                'patientPhone' => $appointment->patient_phone,
                'patientEmail' => $appointment->patient_email,
                'conditionSlug' => $appointment->condition_slug,
                'conditionTitle' => $appointment->condition_title,
                'date' => $appointment->date->format('Y-m-d'),
                'startTime' => substr((string) $appointment->start_time, 0, 5),
                'endTime' => substr((string) $appointment->end_time, 0, 5),
                'status' => $appointment->status,
            ],
        ];
    }

    private function isDateBlocked(string $date): bool
    {
        return BlockedPeriod::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();
    }
}
