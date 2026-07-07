@extends('layouts.admin')

@section('title', 'Availability | Elite Physio Clinics')

@php
    $formatDate = fn ($d) => \Carbon\Carbon::parse($d)->format('D j M Y');
    $formatTime = function ($t) {
        [$h, $m] = array_map('intval', explode(':', substr((string) $t, 0, 5)));
        $p = $h >= 12 ? 'PM' : 'AM';
        $h12 = $h === 0 ? 12 : ($h > 12 ? $h - 12 : $h);
        return $h12 . ':' . str_pad((string) $m, 2, '0', STR_PAD_LEFT) . ' ' . $p;
    };
    $daysDiff = fn ($s, $e) => \Carbon\Carbon::parse($s)->diffInDays(\Carbon\Carbon::parse($e)) + 1;
@endphp

@section('content')
<div class="avail-page" x-data="availabilityManager(@json($clinicHours), {{ config('clinic.slot_duration_minutes', 30) }})">
    <h2 class="admin-page-title">Availability Management</h2>
    <p class="avail-desc">Control when patients can book. Blocked dates and slots are immediately reflected in the booking form.</p>

    <div class="avail-grid">
        <div class="avail-card">
            <div class="avail-card-header">
                <div class="avail-card-icon avail-card-icon-red">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m14.5 16.5-5-5"/><path d="m9.5 16.5 5-5"/></svg>
                </div>
                <div>
                    <h3 class="avail-card-title">Blocked Periods</h3>
                    <p class="avail-card-desc">Block entire date ranges — holidays, vacation, closures</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.availability.periods.store') }}" class="avail-add-form">
                @csrf
                <div class="avail-add-row">
                    <div class="avail-add-field">
                        <label>Start Date</label>
                        <input type="date" name="start_date" required />
                    </div>
                    <div class="avail-add-field">
                        <label>End Date</label>
                        <input type="date" name="end_date" required />
                    </div>
                </div>
                <div class="avail-add-row">
                    <div class="avail-add-field avail-add-field-grow">
                        <label>Reason (optional)</label>
                        <input type="text" name="reason" placeholder="e.g. Annual leave, Bank holiday" />
                    </div>
                    <button type="submit" class="admin-btn-gold avail-add-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        Block Period
                    </button>
                </div>
            </form>

            <div class="avail-items">
                @if($periods->isEmpty())
                    <div class="avail-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m4.243 5.21 14.39 12.472"/></svg>
                        <p>No blocked periods</p>
                    </div>
                @else
                    @foreach($periods as $p)
                        <div class="avail-item">
                            <div class="avail-item-info">
                                <span class="avail-item-dates">{{ $formatDate($p->start_date) }} — {{ $formatDate($p->end_date) }}</span>
                                <span class="avail-item-meta">{{ $daysDiff($p->start_date, $p->end_date) }} day{{ $daysDiff($p->start_date, $p->end_date) > 1 ? 's' : '' }}@if($p->reason) · {{ $p->reason }}@endif</span>
                            </div>
                            <form method="POST" action="{{ route('admin.availability.periods.destroy', $p) }}" onsubmit="return confirm('Remove this blocked period?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="avail-delete" title="Remove">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="avail-card">
            <div class="avail-card-header">
                <div class="avail-card-icon avail-card-icon-amber">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                    <h3 class="avail-card-title">Blocked Time Slots</h3>
                    <p class="avail-card-desc">Block individual slots on specific dates</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.availability.slots.store') }}" class="avail-add-form">
                @csrf
                <div class="avail-add-row">
                    <div class="avail-add-field">
                        <label>Date</label>
                        <input type="date" name="date" x-model="slotDate" @change="slotTime = ''" required />
                    </div>
                    <div class="avail-add-field">
                        <label>Time Slot</label>
                        <select name="start_time" x-model="slotTime" :disabled="!slotDate" required>
                            <option value="">Select time...</option>
                            <template x-for="t in slotTimeOptions" :key="t.value">
                                <option :value="t.value" x-text="t.label"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="avail-add-row">
                    <div class="avail-add-field avail-add-field-grow">
                        <label>Reason (optional)</label>
                        <input type="text" name="reason" placeholder="e.g. Lunch break, Maintenance" />
                    </div>
                    <button type="submit" class="admin-btn-gold avail-add-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        Block Slot
                    </button>
                </div>
            </form>

            <div class="avail-items">
                @if($slots->isEmpty())
                    <div class="avail-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m4.243 5.21 14.39 12.472"/></svg>
                        <p>No blocked slots</p>
                    </div>
                @else
                    @foreach($slots as $s)
                        <div class="avail-item">
                            <div class="avail-item-info">
                                <span class="avail-item-dates">{{ $formatDate($s->date) }} at {{ $formatTime($s->start_time) }}</span>
                                <span class="avail-item-meta">{{ $formatTime($s->start_time) }} — {{ $formatTime($s->end_time) }}@if($s->reason) · {{ $s->reason }}@endif</span>
                            </div>
                            <form method="POST" action="{{ route('admin.availability.slots.destroy', $s) }}" onsubmit="return confirm('Remove this blocked slot?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="avail-delete" title="Remove">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('availabilityManager', (clinicHours, slotDuration) => ({
        clinicHours,
        slotDuration,
        slotDate: '',
        slotTime: '',
        get slotTimeOptions() {
            if (!this.slotDate) return [];
            const d = new Date(this.slotDate + 'T00:00:00');
            const dayName = d.toLocaleDateString('en-US', { weekday: 'long' });
            const hours = this.clinicHours[dayName];
            if (!hours) return [];
            const options = [];
            const [sh, sm] = hours.start.split(':').map(Number);
            const [eh, em] = hours.end.split(':').map(Number);
            for (let min = sh * 60 + sm; min + this.slotDuration <= eh * 60 + em; min += this.slotDuration) {
                const h = Math.floor(min / 60);
                const m = min % 60;
                const value = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
                const p = h >= 12 ? 'PM' : 'AM';
                const h12 = h === 0 ? 12 : (h > 12 ? h - 12 : h);
                options.push({ value, label: h12 + ':' + String(m).padStart(2, '0') + ' ' + p });
            }
            return options;
        }
    }));
});
</script>
@endpush
