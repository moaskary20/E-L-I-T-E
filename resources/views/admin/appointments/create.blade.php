@extends('layouts.admin')

@section('title', 'New Appointment | Elite Physio Clinics')

@php
    $clinicHours = \App\Models\ClinicSetting::hoursMap();
    $slotDuration = config('clinic.slot_duration_minutes', 30);
@endphp

@section('content')
<div class="create-page" x-data="createAppointment(@json($clinicHours), {{ $slotDuration }})">
    <a href="{{ route('admin.dashboard') }}" class="create-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Back to Dashboard
    </a>

    <h2 class="admin-page-title">New Appointment</h2>
    <p class="create-desc">Create an appointment on behalf of a patient — walk-in, phone booking, or referral.</p>

    @if(session('success'))
        <div class="create-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.appointments.store') }}" class="create-form">
        @csrf
        <input type="hidden" name="status" value="confirmed" />

        <div class="create-section">
            <h3 class="create-section-title">Patient Information</h3>
            <div class="create-grid">
                <div class="create-field">
                    <label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Full Name
                    </label>
                    <input name="patient_name" value="{{ old('patient_name') }}" placeholder="John Smith" required />
                    @error('patient_name')<span class="create-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="create-field">
                    <label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        Phone Number
                    </label>
                    <input name="patient_phone" value="{{ old('patient_phone') }}" placeholder="+44 7700 900123" required />
                    @error('patient_phone')<span class="create-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="create-field create-field-full">
                    <label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        Email
                    </label>
                    <input type="email" name="patient_email" value="{{ old('patient_email') }}" placeholder="patient@email.com" required />
                    @error('patient_email')<span class="create-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="create-section">
            <h3 class="create-section-title">Appointment Details</h3>
            <div class="create-grid">
                <div class="create-field create-field-full">
                    <label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 2v2"/><path d="M5 2v2"/><path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 6 6 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2h-1"/><path d="M8 15a6 6 0 0 0 12 0v-3"/></svg>
                        Condition
                    </label>
                    <select name="condition_slug" required>
                        <option value="">Select condition...</option>
                        @foreach($conditions as $c)
                            <option value="{{ $c['slug'] }}" @selected(old('condition_slug') === $c['slug'])>{{ $c['title'] }}</option>
                        @endforeach
                    </select>
                    @error('condition_slug')<span class="create-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="create-field">
                    <label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        Date
                    </label>
                    <input type="date" name="date" x-model="date" @change="time = ''" value="{{ old('date') }}" required />
                    @error('date')<span class="create-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="create-field">
                    <label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Time Slot
                    </label>
                    <select name="start_time" x-model="time" :disabled="!date || timeOptions.length === 0" required>
                        <option value="">{{ old('date') ? '' : 'Select date first' }}</option>
                        <template x-for="t in timeOptions" :key="t.value">
                            <option :value="t.value" x-text="t.label"></option>
                        </template>
                    </select>
                    @error('start_time')<span class="create-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        @if($errors->has('error'))
            <div class="admin-error">{{ $errors->first('error') }}</div>
        @endif

        <div class="create-actions">
            <a href="{{ route('admin.dashboard') }}" class="admin-btn-ghost-v2">Cancel</a>
            <button type="submit" class="admin-btn-gold">Create Appointment</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('createAppointment', (clinicHours, slotDuration) => ({
        clinicHours,
        slotDuration,
        date: @json(old('date', '')),
        time: @json(old('start_time', '')),
        get timeOptions() {
            if (!this.date) return [];
            const d = new Date(this.date + 'T00:00:00');
            const dayName = d.toLocaleDateString('en-US', { weekday: 'long' });
            const hours = this.clinicHours[dayName];
            if (!hours) return [];
            const options = [];
            const [sh, sm] = hours.start.split(':').map(Number);
            const [eh, em] = hours.end.split(':').map(Number);
            const startMin = sh * 60 + sm;
            const endMin = eh * 60 + em;
            for (let min = startMin; min + this.slotDuration <= endMin; min += this.slotDuration) {
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
