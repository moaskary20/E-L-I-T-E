<?php

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AvailabilityController as AdminAvailabilityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SeoSettingsController;
use App\Http\Controllers\Admin\WorkingHoursController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap')
    ->withoutMiddleware([
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);

Route::prefix('api')->group(function () {
    Route::get('/blocked-dates', [AvailabilityController::class, 'blockedDates']);
    Route::get('/unavailable-slots/{date}', [AvailabilityController::class, 'unavailableSlots']);
    Route::get('/clinic-hours', [AvailabilityController::class, 'clinicHours']);
    Route::post('/book', [BookingController::class, 'store']);
});

Route::prefix('clinic-portal')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('new', [AdminAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('appointments', [AdminAppointmentController::class, 'store'])->name('appointments.store');
        Route::patch('appointments/{appointment}', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update');
        Route::delete('appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('appointments.destroy');
        Route::get('availability', [AdminAvailabilityController::class, 'index'])->name('availability.index');
        Route::post('availability/periods', [AdminAvailabilityController::class, 'storePeriod'])->name('availability.periods.store');
        Route::delete('availability/periods/{period}', [AdminAvailabilityController::class, 'destroyPeriod'])->name('availability.periods.destroy');
        Route::post('availability/slots', [AdminAvailabilityController::class, 'storeSlot'])->name('availability.slots.store');
        Route::delete('availability/slots/{slot}', [AdminAvailabilityController::class, 'destroySlot'])->name('availability.slots.destroy');
        Route::get('hours', [WorkingHoursController::class, 'index'])->name('hours.index');
        Route::put('hours', [WorkingHoursController::class, 'update'])->name('hours.update');
        Route::get('seo', [SeoSettingsController::class, 'index'])->name('seo.index');
        Route::put('seo', [SeoSettingsController::class, 'update'])->name('seo.update');
    });
});
