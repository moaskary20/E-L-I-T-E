<script>
window.__bookingConfig = {
    clinicHours: @json($clinicHours),
    conditions: @json($conditions),
    slotDuration: {{ config('clinic.slot_duration_minutes', 30) }},
    bookingWindowWeeks: {{ config('clinic.booking_window_weeks', 4) }},
};
</script>
<div
    x-data="bookingForm()"
    x-cloak
    class="booking-form"
>
    {{-- Confirmation --}}
    <template x-if="booking">
        <div style="text-align: center;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(201,160,66,0.12); border: 2px solid rgba(201,160,66,0.4); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#c9a042" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            </div>
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(28px, 4vw, 36px); font-weight: 300; color: #faf6ef; margin-bottom: 8px;">
                Booking <em style="color: #c9a042;">Confirmed!</em>
            </h3>
            <p style="font-size: 13px; color: rgba(250,246,239,0.45); font-family: Outfit, sans-serif; font-weight: 300; margin-bottom: 32px; line-height: 1.6;">
                Your appointment has been successfully booked.
            </p>
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(201,160,66,0.13); padding: 28px 32px; text-align: left; margin-bottom: 28px;">
                <template x-for="(row, i) in [
                    { label: 'Booking Reference', key: 'bookingReference', mono: true },
                    { label: 'Patient', key: 'patientName' },
                    { label: 'Condition', key: 'conditionTitle' },
                    { label: 'Date', key: 'date', format: 'date' },
                    { label: 'Time', key: 'time', format: 'time' },
                    { label: 'Email', key: 'patientEmail' },
                    { label: 'Phone', key: 'patientPhone' },
                ]" :key="row.label">
                    <div style="display: flex; flex-direction: row; justify-content: space-between; gap: 8px; padding: 10px 0; border-bottom: 1px solid rgba(201,160,66,0.08);" :style="i === 6 ? 'border-bottom: none' : ''">
                        <span style="font-size: 11px; color: rgba(201,160,66,0.6); font-family: Outfit, sans-serif; font-weight: 500; letter-spacing: 0.12em; text-transform: uppercase;" x-text="row.label"></span>
                        <span
                            :style="row.mono ? 'font-size: 13px; color: #c9a042; font-family: monospace; font-weight: 700; letter-spacing: 0.08em;' : 'font-size: 13px; color: rgba(250,246,239,0.75); font-family: Outfit, sans-serif; font-weight: 400;'"
                            x-text="row.format === 'date' ? formatDateHuman(booking.date) : row.format === 'time' ? formatTime12h(booking.startTime) + ' – ' + formatTime12h(booking.endTime) : booking[row.key]"
                        ></span>
                    </div>
                </template>
            </div>
            <button type="button" @click="bookAnother()" class="btn-primary" style="width: auto; background: #c9a042; color: #0a1f13; border: none; padding: 14px 32px; font-size: 12px; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; font-family: Outfit, sans-serif; cursor: pointer; border-radius: 2; display: inline-flex; align-items: center; justify-content: center; gap: 10px;">
                Book Another Appointment
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </div>
    </template>

    <div x-show="!booking">
        {{-- Step indicator --}}
        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 40px;" class="booking-steps">
            <template x-for="(label, i) in ['Condition', 'Date & Time', 'Your Details']" :key="i">
                <div style="display: contents;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div
                            :style="{
                                width: '32px', height: '32px', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center',
                                fontSize: '13px', fontFamily: 'Outfit, sans-serif', fontWeight: 600,
                                background: (i + 1) === currentStep ? '#c9a042' : (i + 1) < currentStep ? 'rgba(201,160,66,0.2)' : 'rgba(255,255,255,0.05)',
                                color: (i + 1) === currentStep ? '#0a1f13' : (i + 1) < currentStep ? '#c9a042' : 'rgba(250,246,239,0.35)',
                                border: (i + 1) === currentStep ? 'none' : '1px solid ' + ((i + 1) < currentStep ? 'rgba(201,160,66,0.3)' : 'rgba(250,246,239,0.1)'),
                                transition: 'all 0.3s ease'
                            }"
                        >
                            <template x-if="(i + 1) < currentStep">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                            </template>
                            <template x-if="(i + 1) >= currentStep">
                                <span x-text="i + 1"></span>
                            </template>
                        </div>
                        <span
                            class="booking-step-label"
                            :style="{
                                fontSize: '12px', fontFamily: 'Outfit, sans-serif',
                                fontWeight: (i + 1) === currentStep ? 600 : 400,
                                color: (i + 1) === currentStep ? '#faf6ef' : (i + 1) < currentStep ? 'rgba(201,160,66,0.7)' : 'rgba(250,246,239,0.3)',
                                letterSpacing: '0.06em', transition: 'all 0.3s ease'
                            }"
                            x-text="label"
                        ></span>
                    </div>
                    <div
                        x-show="i < 2"
                        :style="{
                            width: '48px', height: '1px',
                            background: (i + 1) < currentStep ? 'rgba(201,160,66,0.4)' : 'rgba(250,246,239,0.1)',
                            transition: 'background 0.3s ease'
                        }"
                    ></div>
                </div>
            </template>
        </div>

        {{-- Step 1: Condition --}}
        <div x-show="currentStep === 1">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 300; color: #faf6ef; margin-bottom: 8px; line-height: 1.1;">
                Select Your <em style="color: #c9a042;">Condition</em>
            </h3>
            <p style="font-size: 13px; color: rgba(250,246,239,0.45); font-family: Outfit, sans-serif; font-weight: 300; margin-bottom: 24px; line-height: 1.6;">
                Choose the condition you'd like to be treated for.
            </p>
            <div style="position: relative; margin-bottom: 20px;">
                <select
                    x-model="selectedCondition"
                    @change="conditionError = ''; clearFieldError('condition')"
                    :style="{
                        width: '100%', background: 'rgba(255,255,255,0.03)',
                        border: '1px solid ' + (conditionError ? 'rgba(220,60,60,0.6)' : 'rgba(201,160,66,0.13)'),
                        padding: '15px 44px 15px 18px', color: selectedCondition ? '#faf6ef' : 'rgba(250,246,239,0.45)',
                        fontSize: '14px', fontFamily: 'Outfit, sans-serif', outline: 'none', appearance: 'none', cursor: 'pointer', transition: 'border-color 0.3s'
                    }"
                >
                    <option value="" style="background: #0a1f13;">Select your condition</option>
                    <optgroup label="Adult Conditions" style="background: #0a1f13; color: #c9a042; font-weight: 600;">
                        <template x-for="c in adultConditions()" :key="c.slug">
                            <option :value="c.slug" style="background: #0a1f13; color: #faf6ef; font-weight: 400;" x-text="c.title"></option>
                        </template>
                    </optgroup>
                    <optgroup label="Children's Conditions" style="background: #0a1f13; color: #c9a042; font-weight: 600;">
                        <template x-for="c in paediatricConditions()" :key="c.slug">
                            <option :value="c.slug" style="background: #0a1f13; color: #faf6ef; font-weight: 400;" x-text="c.title"></option>
                        </template>
                    </optgroup>
                    <template x-for="c in otherConditions()" :key="c.slug">
                        <option :value="c.slug" style="background: #0a1f13;" x-text="c.title"></option>
                    </template>
                </select>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(250,246,239,0.35)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); pointer-events: none;"><path d="m6 9 6 6 6-6"/></svg>
            </div>
            <p x-show="conditionError" x-text="conditionError" style="font-size: 12px; color: #e55; font-family: Outfit, sans-serif; margin-bottom: 16px;"></p>
            <button type="button" @click="nextFromCondition()" class="btn-primary" style="width: auto; background: #c9a042; color: #0a1f13; border: none; padding: 14px 32px; font-size: 12px; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; font-family: Outfit, sans-serif; cursor: pointer; border-radius: 2; display: inline-flex; align-items: center; justify-content: center; gap: 10px;">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </div>

        {{-- Step 2: Date & Time --}}
        <div x-show="currentStep === 2">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 300; color: #faf6ef; margin-bottom: 8px; line-height: 1.1;">
                Choose <em style="color: #c9a042;">Date & Time</em>
            </h3>
            <p style="font-size: 13px; color: rgba(250,246,239,0.45); font-family: Outfit, sans-serif; font-weight: 300; margin-bottom: 24px; line-height: 1.6;">
                Pick a convenient date and available time slot.
            </p>

            <div>
                <div style="font-size: 11px; font-family: Outfit, sans-serif; font-weight: 600; color: rgba(201,160,66,0.7); letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 14px;">Select Date</div>
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; margin-bottom: 4px;">
                    <template x-for="d in dayLabels" :key="d">
                        <div style="text-align: center; font-size: 10px; font-family: Outfit, sans-serif; font-weight: 500; color: rgba(250,246,239,0.3); letter-spacing: 0.1em; padding: 6px 0;" x-text="d"></div>
                    </template>
                </div>
                <div style="display: flex; flex-direction: column; gap: 2px;">
                    <template x-for="(week, wi) in calendarWeeks" :key="wi">
                        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px;">
                            <template x-for="(day, di) in week" :key="di">
                                <div style="min-height: 38px;">
                                    <button
                                        x-show="day"
                                        type="button"
                                        :disabled="isDateDisabled(day)"
                                        @click="selectDate(formatDateKey(day))"
                                        :style="{
                                            width: '100%',
                                            padding: '10px 0',
                                            border: formatDateKey(day) === selectedDate ? '1px solid #c9a042' : (formatDateKey(day) === formatDateKey(new Date()) ? '1px solid rgba(201,160,66,0.3)' : '1px solid rgba(255,255,255,0.04)'),
                                            background: formatDateKey(day) === selectedDate ? 'rgba(201,160,66,0.15)' : (blockedDates.has(formatDateKey(day)) ? 'rgba(220,60,60,0.08)' : 'rgba(255,255,255,0.02)'),
                                            color: isDateDisabled(day) ? 'rgba(250,246,239,0.25)' : (formatDateKey(day) === selectedDate ? '#c9a042' : '#faf6ef'),
                                            fontSize: '13px', fontFamily: 'Outfit, sans-serif',
                                            fontWeight: formatDateKey(day) === selectedDate ? 600 : 400,
                                            cursor: isDateDisabled(day) ? 'not-allowed' : 'pointer',
                                            textAlign: 'center', transition: 'all 0.2s ease',
                                            opacity: isDateDisabled(day) ? 0.5 : 1
                                        }"
                                        x-text="day ? day.getDate() : ''"
                                    ></button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="selectedDate" style="margin-top: 24px;">
                <div style="font-size: 11px; font-family: Outfit, sans-serif; font-weight: 600; color: rgba(201,160,66,0.7); letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 14px;">Select Time</div>
                <div x-show="slotsLoading" style="text-align: center; padding: 24px 0; font-size: 13px; color: rgba(250,246,239,0.4); font-family: Outfit, sans-serif;">Loading available times...</div>
                <p x-show="slotsError" x-text="slotsError" style="font-size: 12px; color: #e55; font-family: Outfit, sans-serif;"></p>
                <div x-show="!slotsLoading && !slotsError && slots.length > 0" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;" class="booking-slots-grid">
                    <template x-for="slot in slots" :key="slot.startTime">
                        <button
                            type="button"
                            :disabled="!slot.available"
                            @click="selectedTime = slot.startTime; clearFieldError('time')"
                            :style="{
                                padding: '10px 8px',
                                border: slot.startTime === selectedTime ? '1px solid #c9a042' : '1px solid rgba(255,255,255,0.06)',
                                background: slot.startTime === selectedTime ? 'rgba(201,160,66,0.15)' : (slot.available ? 'rgba(255,255,255,0.02)' : 'rgba(255,255,255,0.01)'),
                                color: !slot.available ? 'rgba(250,246,239,0.2)' : (slot.startTime === selectedTime ? '#c9a042' : 'rgba(250,246,239,0.7)'),
                                fontSize: '12px', fontFamily: 'Outfit, sans-serif',
                                fontWeight: slot.startTime === selectedTime ? 600 : 400,
                                cursor: slot.available ? 'pointer' : 'not-allowed',
                                textAlign: 'center', transition: 'all 0.2s ease',
                                opacity: slot.available ? 1 : 0.45,
                                textDecoration: slot.available ? 'none' : 'line-through'
                            }"
                            x-text="formatTime12h(slot.startTime) + ' – ' + formatTime12h(slot.endTime)"
                        ></button>
                    </template>
                </div>
                <p x-show="!slotsLoading && !slotsError && slots.length === 0 && selectedDate" style="font-size: 13px; color: rgba(250,246,239,0.35); font-family: Outfit, sans-serif;">No time slots available for this date.</p>
            </div>

            <p x-show="errors.date || errors.time" x-text="errors.date || errors.time" style="font-size: 12px; color: #e55; font-family: Outfit, sans-serif; margin-top: 12px;"></p>

            <div style="display: flex; gap: 12px; margin-top: 24px; flex-direction: row;">
                <button type="button" @click="goToStep(1)" class="btn-ghost" style="background: transparent; color: #faf6ef; border: 1px solid rgba(250,246,239,0.2); padding: 13px 24px; font-size: 12px; font-weight: 500; letter-spacing: 0.14em; text-transform: uppercase; font-family: Outfit, sans-serif; cursor: pointer; border-radius: 2; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Back
                </button>
                <button type="button" @click="nextFromDateTime()" class="btn-primary" style="flex: 1; background: #c9a042; color: #0a1f13; border: none; padding: 14px 32px; font-size: 12px; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; font-family: Outfit, sans-serif; cursor: pointer; border-radius: 2; display: inline-flex; align-items: center; justify-content: center; gap: 10px;">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        {{-- Step 3: Patient Details --}}
        <div x-show="currentStep === 3">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 300; color: #faf6ef; margin-bottom: 8px; line-height: 1.1;">
                Your <em style="color: #c9a042;">Details</em>
            </h3>
            <p style="font-size: 13px; color: rgba(250,246,239,0.45); font-family: Outfit, sans-serif; font-weight: 300; margin-bottom: 24px; line-height: 1.6;">
                Please provide your contact information to complete the booking.
            </p>
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <input type="text" placeholder="Full Name" x-model="patientName" @input="clearFieldError('patientName')" maxlength="100"
                        :style="{ width: '100%', background: 'rgba(255,255,255,0.03)', border: '1px solid ' + (errors.patientName ? 'rgba(220,60,60,0.6)' : 'rgba(201,160,66,0.13)'), padding: '15px 18px', color: '#faf6ef', fontSize: '14px', fontFamily: 'Outfit, sans-serif', outline: 'none', transition: 'border-color 0.3s' }" />
                    <p x-show="errors.patientName" x-text="errors.patientName" style="font-size: 12px; color: #e55; font-family: Outfit, sans-serif; margin-top: 4px;"></p>
                </div>
                <div>
                    <input type="tel" placeholder="+44 7700 900123" x-model="patientPhone" @input="clearFieldError('patientPhone')"
                        :style="{ width: '100%', background: 'rgba(255,255,255,0.03)', border: '1px solid ' + (errors.patientPhone ? 'rgba(220,60,60,0.6)' : 'rgba(201,160,66,0.13)'), padding: '15px 18px', color: '#faf6ef', fontSize: '14px', fontFamily: 'Outfit, sans-serif', outline: 'none', transition: 'border-color 0.3s' }" />
                    <p x-show="errors.patientPhone" x-text="errors.patientPhone" style="font-size: 12px; color: #e55; font-family: Outfit, sans-serif; margin-top: 4px;"></p>
                </div>
                <div>
                    <input type="email" placeholder="john@example.com" x-model="patientEmail" @input="clearFieldError('patientEmail')"
                        :style="{ width: '100%', background: 'rgba(255,255,255,0.03)', border: '1px solid ' + (errors.patientEmail ? 'rgba(220,60,60,0.6)' : 'rgba(201,160,66,0.13)'), padding: '15px 18px', color: '#faf6ef', fontSize: '14px', fontFamily: 'Outfit, sans-serif', outline: 'none', transition: 'border-color 0.3s' }" />
                    <p x-show="errors.patientEmail" x-text="errors.patientEmail" style="font-size: 12px; color: #e55; font-family: Outfit, sans-serif; margin-top: 4px;"></p>
                </div>
                <div style="margin-top: 4px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                        <input type="checkbox" x-model="consent" @change="clearFieldError('consent')" style="margin-top: 3px; accent-color: #c9a042; width: 16px; height: 16px; flex-shrink: 0;" />
                        <span style="font-size: 12px; color: rgba(250,246,239,0.55); font-family: Outfit, sans-serif; line-height: 1.6;">
                            I consent to Elite Physio Clinics processing my personal data for the purpose of booking this appointment.
                            <a href="{{ route('privacy') }}" target="_blank" rel="noopener noreferrer" style="color: #c9a042; text-decoration: underline;">Privacy Policy</a>
                        </span>
                    </label>
                    <p x-show="errors.consent" x-text="errors.consent" style="font-size: 12px; color: #e55; font-family: Outfit, sans-serif; margin-top: 4px; margin-left: 26px;"></p>
                </div>
            </div>

            <div x-show="submitError" style="margin-top: 16px; padding: 12px 16px; background: rgba(220,60,60,0.08); border: 1px solid rgba(220,60,60,0.25); font-size: 13px; color: #e55; font-family: Outfit, sans-serif; line-height: 1.5;" x-text="submitError"></div>

            <div style="display: flex; gap: 12px; margin-top: 24px; flex-direction: row;">
                <button type="button" @click="goToStep(2)" :disabled="submitting" class="btn-ghost" :style="{ background: 'transparent', color: '#faf6ef', border: '1px solid rgba(250,246,239,0.2)', padding: '13px 24px', fontSize: '12px', fontWeight: 500, letterSpacing: '0.14em', textTransform: 'uppercase', fontFamily: 'Outfit, sans-serif', cursor: submitting ? 'not-allowed' : 'pointer', borderRadius: '2px', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: '8px', opacity: submitting ? 0.5 : 1 }">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Back
                </button>
                <button type="button" @click="submitBooking()" :disabled="submitting" class="btn-primary" :style="{ flex: 1, background: submitting ? 'rgba(36,120,212,0.5)' : '#c9a042', color: '#0a1f13', border: 'none', padding: '14px 32px', fontSize: '12px', fontWeight: 700, letterSpacing: '0.16em', textTransform: 'uppercase', fontFamily: 'Outfit, sans-serif', cursor: submitting ? 'not-allowed' : 'pointer', borderRadius: '2px', display: 'inline-flex', alignItems: 'center', justifyContent: 'center', gap: '10px' }">
                    <template x-if="submitting">
                        <span style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: rotate-slow 1s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                            Booking...
                        </span>
                    </template>
                    <template x-if="!submitting">
                        <span>Book Appointment</span>
                    </template>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 767px) {
    .booking-steps { gap: 4px !important; margin-bottom: 28px !important; }
    .booking-step-label { display: none; }
    .booking-slots-grid { grid-template-columns: repeat(2, 1fr) !important; }
}
[x-cloak] { display: none !important; }
</style>
