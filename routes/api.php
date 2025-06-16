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
use App\Http\Controllers\API\CoachController;
use App\Http\Controllers\API\SpecificAvailabilityController;
use App\Http\Controllers\API\SportController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserProfileController;

// Health check endpoint para Railway
// Route::get('/health', fn () => response()->json(['status' => 'ok']));
Route::get('/health', function () {
    return 'ok';
});

// ---------- Endpoints públicos ----------
Route::get('/coaches/{coachId}/availability-slots', [AvailabilitySlotController::class, 'getByCoach']);
Route::get('/specific-availabilities',              [SpecificAvailabilityController::class, 'index']);   // listar slots puntuales libres
Route::get('/available-coaches',                    [BookingController::class, 'getAvailableCoaches']);


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
Route::get('/sports', [SportController::class, 'index']);


// Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Perfil de Usuario
    Route::get('/user/profile', [UserProfileController::class, 'getProfile']);
    Route::put('/user/profile', [UserProfileController::class, 'updateProfile']);
    // Perfil Coach
    Route::get('/coach/profile', [CoachController::class, 'getProfile']);
    Route::put('/coach/profile', [CoachController::class, 'updateProfile']);
    Route::post('/coach/sports', [CoachController::class, 'assignSports']);

    // Disponibilidad recurrente
    Route::apiResource('availability-slots', AvailabilitySlotController::class);

    // Disponibilidad puntual
    Route::post('/specific-availabilities',               [SpecificAvailabilityController::class, 'store']);
    Route::patch('/specific-availabilities/{id}/book',    [SpecificAvailabilityController::class, 'book']);

    // Reservas
    Route::apiResource('bookings', BookingController::class)->except(['update', 'destroy']);
    Route::patch('bookings/{id}/cancel',       [BookingController::class, 'cancel']);
    Route::patch('bookings/{id}/mark-as-paid', [BookingPaymentController::class, 'markAsPaid']);
});
