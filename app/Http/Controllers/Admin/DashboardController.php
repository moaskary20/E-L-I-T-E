<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today()->format('Y-m-d');
        $weekEnd = Carbon::today()->addDays(7)->format('Y-m-d');

        $todayAppointments = Appointment::where('date', $today)->orderBy('start_time')->get();
        $upcomingAppointments = Appointment::where('date', '>', $today)
            ->where('date', '<=', $weekEnd)
            ->where('status', 'confirmed')
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(8)
            ->get();
        $recentBookings = Appointment::orderByDesc('created_at')->limit(5)->get();

        $stats = [
            'todayTotal' => $todayAppointments->count(),
            'todayConfirmed' => $todayAppointments->where('status', 'confirmed')->count(),
            'todayCompleted' => $todayAppointments->where('status', 'completed')->count(),
            'todayCancelled' => $todayAppointments->where('status', 'cancelled')->count(),
            'weekTotal' => $upcomingAppointments->count(),
            'weekConfirmed' => $upcomingAppointments->where('status', 'confirmed')->count(),
        ];

        return view('admin.dashboard', compact('todayAppointments', 'upcomingAppointments', 'recentBookings', 'stats'));
    }
}
