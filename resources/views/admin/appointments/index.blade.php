@extends('layouts.admin')

@section('title', 'Appointments | Elite Physio Clinics')

@php
    $statusColors = ['confirmed' => '#22c55e', 'cancelled' => '#ef4444', 'completed' => '#3b82f6', 'rescheduled' => '#f59e0b'];
    $formatTime = function ($t) {
        [$h, $m] = array_map('intval', explode(':', substr((string) $t, 0, 5)));
        $p = $h >= 12 ? 'PM' : 'AM';
        $h12 = $h === 0 ? 12 : ($h > 12 ? $h - 12 : $h);
        return $h12 . ':' . str_pad((string) $m, 2, '0', STR_PAD_LEFT) . ' ' . $p;
    };
    $formatDate = fn ($d) => \Carbon\Carbon::parse($d)->format('D j M Y');
@endphp

@section('content')
<div class="apt-page">
    <div class="apt-header">
        <h2 class="admin-page-title">Appointments</h2>
        <span class="apt-count">{{ $appointments->count() }} total</span>
    </div>

    <div class="apt-status-tabs">
        @foreach(['all', 'confirmed', 'completed', 'cancelled', 'rescheduled'] as $status)
            <a href="{{ route('admin.appointments.index', array_merge(request()->except('status', 'page'), ['status' => $status])) }}"
               class="apt-status-tab {{ $statusFilter === $status ? 'active' : '' }}">
                @if($status !== 'all')
                    <span class="apt-tab-dot" style="background: {{ $statusColors[$status] }};"></span>
                @endif
                <span class="apt-tab-label">{{ $status === 'all' ? 'All' : ucfirst($status) }}</span>
                <span class="apt-tab-count">{{ $statusCounts[$status] }}</span>
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.appointments.index') }}" class="apt-toolbar">
        <input type="hidden" name="status" value="{{ $statusFilter }}" />
        <div class="apt-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="search" placeholder="Search by name, reference, phone..." value="{{ $search }}" />
        </div>
        <div class="apt-date-filters">
            <div class="apt-date-field">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                <input type="date" name="date_from" value="{{ request('date_from') }}" />
            </div>
            <span class="apt-date-sep">to</span>
            <div class="apt-date-field">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                <input type="date" name="date_to" value="{{ request('date_to') }}" />
            </div>
            <button type="submit" class="admin-btn-gold" style="padding: 8px 14px; font-size: 12px;">Filter</button>
            @if(request('date_from') || request('date_to'))
                <a href="{{ route('admin.appointments.index', ['status' => $statusFilter, 'search' => $search]) }}" class="apt-clear-dates">Clear</a>
            @endif
        </div>
    </form>

    @if($appointments->isEmpty())
        <div class="apt-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            <p>No appointments match your filters</p>
        </div>
    @else
        <div class="apt-table-container">
            <table class="apt-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Phone</th>
                        <th>Condition</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $apt)
                        <tr x-data="{ open: false, reschedule: false }">
                            <td><code class="apt-ref">{{ $apt->booking_reference }}</code></td>
                            <td class="apt-date-cell">{{ $formatDate($apt->date) }}</td>
                            <td class="apt-time-cell">{{ $formatTime($apt->start_time) }} <span class="apt-time-dim">- {{ $formatTime($apt->end_time) }}</span></td>
                            <td class="apt-name-cell">
                                <span class="apt-patient-name">{{ $apt->patient_name }}</span>
                                <span class="apt-patient-email">{{ $apt->patient_email }}</span>
                            </td>
                            <td>{{ $apt->patient_phone }}</td>
                            <td><span class="apt-condition">{{ $apt->condition_title }}</span></td>
                            <td>
                                <span class="apt-status" style="--status-color: {{ $statusColors[$apt->status] ?? '#666' }};">
                                    <span class="apt-status-dot"></span>
                                    {{ $apt->status }}
                                </span>
                            </td>
                            <td>
                                <div class="actions-wrap" style="position: relative;">
                                    <button type="button" class="actions-trigger" @click="open = !open">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false; reschedule = false" x-cloak class="actions-dropdown" style="position: absolute; right: 0; top: calc(100% + 4px);">
                                        @if($apt->status === 'confirmed')
                                            <form method="POST" action="{{ route('admin.appointments.update', $apt) }}" onsubmit="return confirm('Mark appointment as completed?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed" />
                                                <button type="submit" class="actions-item actions-item-complete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                                                    Mark Complete
                                                </button>
                                            </form>
                                            <button type="button" class="actions-item actions-item-reschedule" @click="reschedule = !reschedule">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                                Reschedule
                                            </button>
                                            <form method="POST" action="{{ route('admin.appointments.update', $apt) }}" onsubmit="return confirm('Cancel appointment for {{ $apt->patient_name }}?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled" />
                                                <button type="submit" class="actions-item actions-item-cancel">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                                    Cancel
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.appointments.update', $apt) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed" />
                                                <button type="submit" class="actions-item actions-item-complete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                                    Reconfirm
                                                </button>
                                            </form>
                                        @endif
                                        <div class="actions-divider"></div>
                                        <form method="POST" action="{{ route('admin.appointments.destroy', $apt) }}" onsubmit="return confirm('Permanently delete appointment for {{ $apt->patient_name }}? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="actions-item actions-item-delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                Delete Permanently
                                            </button>
                                        </form>
                                        <div x-show="reschedule" class="actions-reschedule">
                                            <div class="actions-reschedule-header">
                                                <span>Reschedule to:</span>
                                                <button type="button" @click="reschedule = false" class="actions-reschedule-close">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.appointments.update', $apt) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rescheduled" />
                                                <input type="date" name="date" required />
                                                <input type="time" name="start_time" step="1800" required />
                                                <button type="submit" class="admin-btn-gold actions-confirm-btn">Confirm Reschedule</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
