@extends('layouts.admin')

@section('title', 'Dashboard | Elite Physio Clinics')

@php
    $statusDot = ['confirmed' => '#22c55e', 'cancelled' => '#ef4444', 'completed' => '#3b82f6', 'rescheduled' => '#f59e0b'];
    $formatTime = function ($t) {
        [$h, $m] = array_map('intval', explode(':', substr((string) $t, 0, 5)));
        $p = $h >= 12 ? 'PM' : 'AM';
        $h12 = $h === 0 ? 12 : ($h > 12 ? $h - 12 : $h);
        return $h12 . ':' . str_pad((string) $m, 2, '0', STR_PAD_LEFT) . ' ' . $p;
    };
    $formatDate = fn ($d) => \Carbon\Carbon::parse($d)->format('D j M');
    $timeAgo = function ($iso) {
        $diff = now()->diffInMinutes(\Carbon\Carbon::parse($iso));
        if ($diff < 1) return 'Just now';
        if ($diff < 60) return $diff . 'm ago';
        $hrs = intdiv($diff, 60);
        if ($hrs < 24) return $hrs . 'h ago';
        return intdiv($hrs, 24) . 'd ago';
    };
@endphp

@section('content')
<div class="dash">
    <div class="dash-header">
        <div>
            <h2 class="dash-title">Dashboard</h2>
            <p class="dash-date">{{ now()->format('l j F Y') }}</p>
        </div>
        <a href="{{ route('admin.appointments.create') }}" class="admin-btn-gold">
            <span class="btn-icon">+</span> New Appointment
        </a>
    </div>

    <div class="dash-stats">
        <div class="dash-stat-card dash-stat-primary">
            <div class="dash-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
            </div>
            <div class="dash-stat-body">
                <span class="dash-stat-value">{{ $stats['todayTotal'] }}</span>
                <span class="dash-stat-label">Today's Appointments</span>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon dash-stat-icon-green">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            </div>
            <div class="dash-stat-body">
                <span class="dash-stat-value">{{ $stats['todayConfirmed'] }}</span>
                <span class="dash-stat-label">Confirmed Today</span>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon dash-stat-icon-blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
            </div>
            <div class="dash-stat-body">
                <span class="dash-stat-value">{{ $stats['todayCompleted'] }}</span>
                <span class="dash-stat-label">Completed Today</span>
            </div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-icon dash-stat-icon-amber">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="dash-stat-body">
                <span class="dash-stat-value">{{ $stats['weekTotal'] }}</span>
                <span class="dash-stat-label">This Week</span>
            </div>
        </div>
    </div>

    <div class="dash-grid">
        <div class="dash-card dash-card-wide">
            <div class="dash-card-header">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Today's Schedule
                </h3>
                <a href="{{ route('admin.appointments.index') }}" class="dash-card-link">View all
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
            @if($todayAppointments->isEmpty())
                <div class="dash-card-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    <p>No appointments scheduled for today</p>
                </div>
            @else
                <div class="dash-timeline">
                    @foreach($todayAppointments as $apt)
                        <div class="dash-timeline-item">
                            <div class="dash-timeline-time">
                                <span class="dash-time-primary">{{ $formatTime($apt->start_time) }}</span>
                                <span class="dash-time-end">{{ $formatTime($apt->end_time) }}</span>
                            </div>
                            <div class="dash-timeline-dot" style="background: {{ $statusDot[$apt->status] ?? '#64748b' }};"></div>
                            <div class="dash-timeline-content">
                                <span class="dash-timeline-name">{{ $apt->patient_name }}</span>
                                <span class="dash-timeline-condition">{{ $apt->condition_title }}</span>
                            </div>
                            <span class="dash-badge dash-badge-{{ $apt->status }}">{{ $apt->status }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="dash-sidebar">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        Upcoming
                    </h3>
                </div>
                @if($upcomingAppointments->isEmpty())
                    <p class="dash-card-empty-sm">No upcoming appointments this week</p>
                @else
                    <div class="dash-upcoming-list">
                        @foreach($upcomingAppointments as $apt)
                            <div class="dash-upcoming-item">
                                <div class="dash-upcoming-date">{{ $formatDate($apt->date) }}</div>
                                <div class="dash-upcoming-detail">
                                    <span class="dash-upcoming-name">{{ $apt->patient_name }}</span>
                                    <span class="dash-upcoming-time">{{ $formatTime($apt->start_time) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="dash-card">
                <div class="dash-card-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        Recent Bookings
                    </h3>
                </div>
                @if($recentBookings->isEmpty())
                    <p class="dash-card-empty-sm">No recent bookings</p>
                @else
                    <div class="dash-recent-list">
                        @foreach($recentBookings as $apt)
                            <div class="dash-recent-item">
                                <div class="dash-recent-left">
                                    <span class="dash-recent-ref">{{ $apt->booking_reference }}</span>
                                    <span class="dash-recent-name">{{ $apt->patient_name }}</span>
                                </div>
                                <span class="dash-recent-time">{{ $timeAgo($apt->created_at) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
