<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\API\BookingPaymentController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\AvailabilitySlotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoints públicos (no requieren autenticación)
Route::get('/coaches/{coachId}/availability-slots', [AvailabilitySlotController::class, 'getByCoach']);
Route::get('/available-coaches', [BookingController::class, 'getAvailableCoaches']);


// Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Rutas para disponibilidad recurrente
    Route::apiResource('availability-slots', AvailabilitySlotController::class);

    // Rutas para reservas
    Route::apiResource('bookings', BookingController::class)->except(['update', 'destroy']);
    Route::patch('bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Ruta para marcar una reserva como pagada (ya existente)
    Route::patch('bookings/{id}/mark-as-paid', [BookingPaymentController::class, 'markAsPaid']);
});
