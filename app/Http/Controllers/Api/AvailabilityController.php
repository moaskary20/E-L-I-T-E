<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
use App\Services\AvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function blockedDates(Request $request, AvailabilityService $service): JsonResponse
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        return response()->json(
            $service->getBlockedDates($request->from_date, $request->to_date)
        );
    }

    public function unavailableSlots(string $date, AvailabilityService $service): JsonResponse
    {
        return response()->json($service->getUnavailableSlots($date));
    }

    public function clinicHours(): JsonResponse
    {
        return response()->json(ClinicSetting::hoursMap());
    }
}
