<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingConfirmationMail;
use App\Mail\BookingNotificationMail;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, BookingService $bookingService): JsonResponse
    {
        $validated = $request->validated();
        $conditionTitle = collect(config('clinic.conditions'))
            ->firstWhere('slug', $validated['condition_slug'])['title'] ?? $validated['condition_slug'];

        $result = $bookingService->book([
            'patient_name' => $validated['patient_name'],
            'patient_phone' => $validated['patient_phone'],
            'patient_email' => $validated['patient_email'],
            'condition_slug' => $validated['condition_slug'],
            'condition_title' => $conditionTitle,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
        ]);

        if ($result['success'] && isset($result['data'])) {
            $booking = $result['data'];
            try {
                Mail::to(config('clinic.email'))->send(new BookingNotificationMail($booking));
                Mail::to($booking['patientEmail'])->send(new BookingConfirmationMail($booking));
            } catch (\Throwable) {
                // Don't block confirmation if email fails
            }
        }

        return response()->json($result);
    }
}
