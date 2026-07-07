<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Appointment::query()->orderByDesc('date')->orderBy('start_time');

        $statusFilter = $request->get('status', 'all');
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $appointments = $query->get();
        $search = $request->get('search', '');

        if ($search) {
            $searchLower = strtolower($search);
            $appointments = $appointments->filter(fn ($a) =>
                str_contains(strtolower($a->patient_name), $searchLower) ||
                str_contains(strtolower($a->booking_reference), $searchLower) ||
                str_contains($a->patient_phone, $search) ||
                str_contains(strtolower($a->condition_title), $searchLower)
            );
        }

        $allForCounts = Appointment::all();
        $statusCounts = [
            'all' => $allForCounts->count(),
            'confirmed' => $allForCounts->where('status', 'confirmed')->count(),
            'cancelled' => $allForCounts->where('status', 'cancelled')->count(),
            'completed' => $allForCounts->where('status', 'completed')->count(),
            'rescheduled' => $allForCounts->where('status', 'rescheduled')->count(),
        ];

        return view('admin.appointments.index', compact(
            'appointments', 'statusFilter', 'search', 'statusCounts'
        ));
    }

    public function create(): View
    {
        return view('admin.appointments.create', [
            'conditions' => config('clinic.conditions'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:100',
            'patient_phone' => 'required|string',
            'patient_email' => 'required|email',
            'condition_slug' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'status' => 'required|in:confirmed,cancelled,completed,rescheduled',
        ]);

        $conditionTitle = collect(config('clinic.conditions'))
            ->firstWhere('slug', $validated['condition_slug'])['title'] ?? $validated['condition_slug'];

        $startTime = strlen($validated['start_time']) === 5
            ? $validated['start_time'].':00'
            : $validated['start_time'];
        $duration = config('clinic.slot_duration_minutes', 30);
        $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $startTime)
            ->addMinutes($duration)->format('H:i:s');

        $id = (string) \Illuminate\Support\Str::uuid();
        Appointment::create([
            'id' => $id,
            'booking_reference' => strtoupper(substr(str_replace('-', '', $id), 0, 8)),
            'patient_name' => $validated['patient_name'],
            'patient_phone' => $validated['patient_phone'],
            'patient_email' => $validated['patient_email'],
            'condition_slug' => $validated['condition_slug'],
            'condition_title' => $conditionTitle,
            'date' => $validated['date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed,rescheduled',
            'date' => 'nullable|date',
            'start_time' => 'nullable',
        ]);

        $appointment->status = $validated['status'];

        if ($validated['status'] === 'rescheduled' && $request->filled('date') && $request->filled('start_time')) {
            $startTime = strlen($request->start_time) === 5
                ? $request->start_time.':00'
                : $request->start_time;
            $duration = config('clinic.slot_duration_minutes', 30);
            $appointment->date = $request->date;
            $appointment->start_time = $startTime;
            $appointment->end_time = \Carbon\Carbon::createFromFormat('H:i:s', $startTime)
                ->addMinutes($duration)->format('H:i:s');
        }

        $appointment->save();

        return back()->with('success', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return back()->with('success', 'Appointment deleted.');
    }
}
