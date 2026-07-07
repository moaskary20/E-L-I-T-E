/**
 * Elite Physio Clinics — multi-step booking form (Alpine.js)
 */

document.addEventListener('alpine:init', function () {
  Alpine.data('bookingForm', function () {
    const cfg = window.__bookingConfig || {};
    const clinicHours = cfg.clinicHours || {};
    const conditions = cfg.conditions || [];
    const slotDuration = cfg.slotDuration || 30;
    const bookingWindowWeeks = cfg.bookingWindowWeeks || 4;

    return {
      clinicHours: clinicHours,
      conditions: conditions,
      slotDuration: slotDuration,
      bookingWindowWeeks: bookingWindowWeeks,

      currentStep: 1,
      selectedCondition: '',
      selectedDate: '',
      selectedTime: '',
      patientName: '',
      patientPhone: '',
      patientEmail: '',
      consent: false,
      errors: {},
      submitError: '',
      submitting: false,
      booking: null,

      blockedDates: new Set(),
      slots: [],
      slotsLoading: false,
      slotsError: '',
      conditionError: '',

      calendarWeeks: [],
      dayLabels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],

      init() {
        this.buildCalendar();
        this.loadBlockedDates();
      },

      get isMobile() {
        return window.innerWidth < 768;
      },

      buildCalendar() {
        const dates = [];
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const end = new Date(today);
        end.setDate(end.getDate() + this.bookingWindowWeeks * 7);

        const current = new Date(today);
        while (current <= end) {
          dates.push(new Date(current));
          current.setDate(current.getDate() + 1);
        }

        const weeks = [];
        let week = [];
        const firstDay = dates[0].getDay();
        const mondayOffset = firstDay === 0 ? 6 : firstDay - 1;
        for (let i = 0; i < mondayOffset; i++) week.push(null);

        for (const d of dates) {
          week.push(d);
          if (week.length === 7) {
            weeks.push(week);
            week = [];
          }
        }
        if (week.length > 0) {
          while (week.length < 7) week.push(null);
          weeks.push(week);
        }
        this.calendarWeeks = weeks;
      },

      formatDateKey(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + day;
      },

      async loadBlockedDates() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const end = new Date(today);
        end.setDate(end.getDate() + this.bookingWindowWeeks * 7);
        const fromDate = this.formatDateKey(today);
        const toDate = this.formatDateKey(end);

        try {
          const res = await fetch('/api/blocked-dates?from_date=' + fromDate + '&to_date=' + toDate);
          const data = await res.json();
          this.blockedDates = new Set((data || []).map(function (d) {
            return d.blocked_date;
          }));
        } catch (e) {
          this.blockedDates = new Set();
        }
      },

      isDateDisabled(day) {
        const dateStr = this.formatDateKey(day);
        const dayName = day.toLocaleDateString('en-US', { weekday: 'long' });
        const hours = this.clinicHours[dayName];
        const isClosedDay = hours === null || hours === undefined;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const isPast = day < today;
        const isBlocked = this.blockedDates.has(dateStr);
        return isClosedDay || isPast || isBlocked;
      },

      selectDate(dateStr) {
        this.selectedDate = dateStr;
        this.selectedTime = '';
        this.errors.date = '';
        this.errors.time = '';
        this.loadSlots();
      },

      generateSlots(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        const dayName = d.toLocaleDateString('en-US', { weekday: 'long' });
        const hours = this.clinicHours[dayName];
        if (!hours) return [];

        const slots = [];
        const parts = hours.start.split(':').map(Number);
        const endParts = hours.end.split(':').map(Number);
        const startMin = parts[0] * 60 + parts[1];
        const endMin = endParts[0] * 60 + endParts[1];

        for (let min = startMin; min + this.slotDuration <= endMin; min += this.slotDuration) {
          const sh = Math.floor(min / 60);
          const sm = min % 60;
          const eh = Math.floor((min + this.slotDuration) / 60);
          const em = (min + this.slotDuration) % 60;
          slots.push({
            startTime: String(sh).padStart(2, '0') + ':' + String(sm).padStart(2, '0'),
            endTime: String(eh).padStart(2, '0') + ':' + String(em).padStart(2, '0'),
            available: true,
          });
        }
        return slots;
      },

      async loadSlots() {
        if (!this.selectedDate) {
          this.slots = [];
          return;
        }

        this.slotsLoading = true;
        this.slotsError = '';
        const allSlots = this.generateSlots(this.selectedDate);

        try {
          const res = await fetch('/api/unavailable-slots/' + this.selectedDate);
          const data = await res.json();
          const unavailable = new Set((data || []).map(function (s) {
            return (s.start_time || '').substring(0, 5);
          }));
          this.slots = allSlots.map(function (slot) {
            return Object.assign({}, slot, {
              available: !unavailable.has(slot.startTime),
            });
          });
        } catch (e) {
          this.slotsError = 'Unable to check availability. Please try again or contact us directly.';
          this.slots = allSlots;
        } finally {
          this.slotsLoading = false;
        }
      },

      formatTime12h(time24) {
        const parts = time24.split(':').map(Number);
        const h = parts[0];
        const m = parts[1];
        const period = h >= 12 ? 'PM' : 'AM';
        const h12 = h === 0 ? 12 : h > 12 ? h - 12 : h;
        return h12 + ':' + String(m).padStart(2, '0') + ' ' + period;
      },

      formatDateHuman(dateStr) {
        const date = new Date(dateStr + 'T12:00:00');
        return date.toLocaleDateString('en-GB', {
          weekday: 'long',
          day: 'numeric',
          month: 'long',
          year: 'numeric',
        });
      },

      adultConditions() {
        return this.conditions.filter(function (c) {
          return c.category === 'adult';
        });
      },

      paediatricConditions() {
        return this.conditions.filter(function (c) {
          return c.category === 'paediatric';
        });
      },

      otherConditions() {
        return this.conditions.filter(function (c) {
          return !c.category;
        });
      },

      conditionTitle(slug) {
        const found = this.conditions.find(function (c) {
          return c.slug === slug;
        });
        return found ? found.title : slug;
      },

      goToStep(step) {
        this.currentStep = step;
      },

      nextFromCondition() {
        if (!this.selectedCondition) {
          this.conditionError = 'Please select a condition to continue';
          return;
        }
        this.conditionError = '';
        this.currentStep = 2;
      },

      nextFromDateTime() {
        const errs = {};
        if (!this.selectedDate) errs.date = 'Please select a date';
        if (!this.selectedTime) errs.time = 'Please select a time';
        if (Object.keys(errs).length) {
          this.errors = errs;
          return;
        }
        this.errors = {};
        this.currentStep = 3;
      },

      clearFieldError(field) {
        delete this.errors[field];
        this.submitError = '';
      },

      validatePatient() {
        const errs = {};
        if (!this.patientName.trim()) errs.patientName = 'Name is required';
        else if (this.patientName.length > 100) errs.patientName = 'Name must be 100 characters or less';
        if (!this.patientPhone.trim()) errs.patientPhone = 'Phone number is required';
        else if (!/^(\+44|0)[\s\-]?[\d\s\-]{9,12}$/.test(this.patientPhone.trim())) {
          errs.patientPhone = 'Please enter a valid UK phone number (e.g. +44 7700 900123).';
        }
        if (!this.patientEmail.trim()) errs.patientEmail = 'Email is required';
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.patientEmail.trim())) {
          errs.patientEmail = 'Please enter a valid email address';
        }
        if (!this.consent) errs.consent = 'You must consent to the privacy policy to proceed.';
        return errs;
      },

      async submitBooking() {
        const errs = this.validatePatient();
        if (Object.keys(errs).length) {
          this.errors = errs;
          return;
        }

        this.submitting = true;
        this.submitError = '';
        this.errors = {};

        const csrf = document.querySelector('meta[name="csrf-token"]');
        const token = csrf ? csrf.getAttribute('content') : '';

        try {
          const res = await fetch('/api/book', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Accept: 'application/json',
              'X-CSRF-TOKEN': token,
            },
            body: JSON.stringify({
              patient_name: this.patientName.trim(),
              patient_phone: this.patientPhone.trim(),
              patient_email: this.patientEmail.trim(),
              condition_slug: this.selectedCondition,
              date: this.selectedDate,
              start_time: this.selectedTime,
              consent: this.consent,
            }),
          });

          const response = await res.json();

          if (response.success && response.data) {
            this.booking = response.data;
          } else {
            this.submitError = response.error || 'This time slot is no longer available. Please go back and select a different time.';
          }
        } catch (e) {
          this.submitError = 'Network error. Please check your connection and try again.';
        } finally {
          this.submitting = false;
        }
      },

      bookAnother() {
        this.currentStep = 1;
        this.selectedCondition = '';
        this.selectedDate = '';
        this.selectedTime = '';
        this.patientName = '';
        this.patientPhone = '';
        this.patientEmail = '';
        this.consent = false;
        this.errors = {};
        this.submitError = '';
        this.booking = null;
        this.slots = [];
        this.conditionError = '';
      },
    };
  });
});
