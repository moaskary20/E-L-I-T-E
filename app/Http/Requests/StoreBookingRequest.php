<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $slugs = collect(config('clinic.conditions'))->pluck('slug')->all();

        return [
            'patient_name' => ['required', 'string', 'max:100'],
            'patient_phone' => ['required', 'regex:/^(\+44|0)[\s\-]?[\d\s\-]{9,12}$/'],
            'patient_email' => ['required', 'email'],
            'condition_slug' => ['required', Rule::in($slugs)],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'regex:/^\d{2}:\d{2}$/'],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'patient_phone.regex' => 'Please enter a valid UK phone number (e.g. +44 7700 900123).',
            'consent.accepted' => 'You must consent to the privacy policy to proceed.',
        ];
    }
}
