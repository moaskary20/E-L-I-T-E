@extends('layouts.admin')

@section('title', 'Working Hours | Elite Physio Clinics')

@section('content')
<script>
window.__workingHoursConfig = {
    days: @json($days),
    defaults: @json($defaultDays),
};
</script>
<div class="wh-page" x-data="workingHours()">
    <h2 class="admin-page-title">Working Hours</h2>
    <p class="wh-desc">Set the clinic's operating hours. Changes are immediately reflected in the patient booking form across the website.</p>

    <form method="POST" action="{{ route('admin.hours.update') }}">
        @csrf
        @method('PUT')

        <div class="wh-card">
            <div class="wh-card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Weekly Schedule</span>
            </div>

            <div class="wh-table">
                <div class="wh-table-header">
                    <span class="wh-col-day">Day</span>
                    <span class="wh-col-status">Status</span>
                    <span class="wh-col-start">Opens</span>
                    <span class="wh-col-end">Closes</span>
                    <span class="wh-col-preview">Preview</span>
                </div>

                <template x-for="(day, index) in days" :key="day.day_of_week">
                    <div class="wh-row" :class="{ 'wh-row-closed': !day.is_open }">
                        <span class="wh-col-day wh-day-name" x-text="day.day_of_week"></span>

                        <div class="wh-col-status">
                            <button type="button" class="wh-toggle" :class="{ 'wh-toggle-on': day.is_open }" @click="toggleDay(index)">
                                <span class="wh-toggle-thumb"></span>
                            </button>
                            <span class="wh-toggle-label" :class="{ 'wh-closed-label': !day.is_open }" x-text="day.is_open ? 'Open' : 'Closed'"></span>
                            <input type="hidden" :name="'days[' + index + '][day_of_week]'" :value="day.day_of_week" />
                            <input type="hidden" :name="'days[' + index + '][is_open]'" :value="day.is_open ? 1 : 0" />
                        </div>

                        <div class="wh-col-start">
                            <template x-if="day.is_open">
                                <input type="time" class="wh-time-input" :name="'days[' + index + '][start_time]'" x-model="day.start_time" />
                            </template>
                            <template x-if="!day.is_open">
                                <span class="wh-na">—</span>
                            </template>
                        </div>

                        <div class="wh-col-end">
                            <template x-if="day.is_open">
                                <input type="time" class="wh-time-input" :name="'days[' + index + '][end_time]'" x-model="day.end_time" />
                            </template>
                            <template x-if="!day.is_open">
                                <span class="wh-na">—</span>
                            </template>
                        </div>

                        <div class="wh-col-preview">
                            <template x-if="day.is_open && day.start_time && day.end_time">
                                <span class="wh-preview-text" x-text="formatTimeLabel(day.start_time) + ' – ' + formatTimeLabel(day.end_time)"></span>
                            </template>
                            <template x-if="!day.is_open || !day.start_time || !day.end_time">
                                <span class="wh-preview-closed">Closed</span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div class="wh-actions">
                <button type="button" @click="resetDefaults()" class="admin-btn-ghost-v2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset to Defaults
                </button>
                <button type="submit" class="admin-btn-gold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg>
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('workingHours', () => ({
        days: window.__workingHoursConfig?.days ?? [],
        defaults: window.__workingHoursConfig?.defaults ?? [],
        toggleDay(index) {
            const day = this.days[index];
            day.is_open = !day.is_open;
            if (!day.is_open) {
                day.start_time = null;
                day.end_time = null;
            } else if (!day.start_time) {
                day.start_time = '09:00';
                day.end_time = '17:00';
            }
        },
        resetDefaults() {
            this.days = JSON.parse(JSON.stringify(this.defaults));
        },
        formatTimeLabel(t) {
            const [h, m] = t.split(':').map(Number);
            const p = h >= 12 ? 'PM' : 'AM';
            const h12 = h === 0 ? 12 : (h > 12 ? h - 12 : h);
            return h12 + ':' + String(m).padStart(2, '0') + ' ' + p;
        }
    }));
});
</script>
@endpush
