<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedPeriod;
use App\Models\BlockedSlot;
use App\Models\ClinicSetting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        return view('admin.availability.index', [
            'periods' => BlockedPeriod::orderBy('start_date')->get(),
            'slots' => BlockedSlot::orderBy('date')->orderBy('start_time')->get(),
            'clinicHours' => ClinicSetting::hoursMap(),
        ]);
    }

    public function storePeriod(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedPeriod::create($validated);

        return back()->with('success', 'Period blocked successfully.');
    }

    public function destroyPeriod(BlockedPeriod $period): RedirectResponse
    {
        $period->delete();

        return back()->with('success', 'Period unblocked.');
    }

    public function storeSlot(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'reason' => 'nullable|string|max:255',
        ]);

        $startTime = strlen($validated['start_time']) === 5
            ? $validated['start_time'].':00'
            : $validated['start_time'];
        $duration = config('clinic.slot_duration_minutes', 30);
        $endTime = Carbon::createFromFormat('H:i:s', $startTime)
            ->addMinutes($duration)->format('H:i:s');

        BlockedSlot::create([
            'date' => $validated['date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'reason' => $validated['reason'] ?? null,
        ]);

        return back()->with('success', 'Slot blocked successfully.');
    }

    public function destroySlot(BlockedSlot $slot): RedirectResponse
    {
        $slot->delete();

        return back()->with('success', 'Slot unblocked.');
    }
}
