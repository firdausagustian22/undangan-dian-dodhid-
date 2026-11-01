<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root route: Menampilkan Tampilan Undangan Pernikahan (public/undangan)
Route::get('/', [InvitationController::class, 'show'])->name('home');

// Undangan Digital Personal via Slug (/to/{slug})
Route::get('/to/{slug}', [InvitationController::class, 'show'])->name('invitation.slug');

// Backend Bridge untuk form RSVP, Ucapan & Gift dari frontend template undangan
Route::match(['GET', 'POST'], '/invitation/backend-bridge', [InvitationController::class, 'backendBridge'])->name('invitation.bridge');

// Fallback submit routes jika menggunakan form standar
Route::post('/invitation/rsvp', [InvitationController::class, 'submitRsvp'])->name('invitation.rsvp');
Route::post('/invitation/wishes', [InvitationController::class, 'submitWish'])->name('invitation.wishes');

// =========================================================================
// AUTHENTICATION ROUTES (LOGIN & LOGOUT)
// =========================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================================
// DASHBOARD MONITORING & ADMIN PANEL (/dashboard) — DILINDUNGI LOGIN (AUTH)
// =========================================================================
Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::post('/guests', [DashboardController::class, 'storeGuest'])->name('guests.store');
    Route::put('/guests/{guest}', [DashboardController::class, 'updateGuest'])->name('guests.update');
    Route::delete('/guests/{guest}', [DashboardController::class, 'destroyGuest'])->name('guests.destroy');
    Route::patch('/guests/{guest}/rsvp', [DashboardController::class, 'updateRsvp'])->name('guests.updateRsvp');
    Route::get('/export/csv', [DashboardController::class, 'exportCsv'])->name('guests.export');
    Route::get('/api/stats', [DashboardController::class, 'apiStats'])->name('api.stats');

    // Wish Moderation
    Route::post('/wishes/{wish}/toggle-pin', [DashboardController::class, 'togglePinWish'])->name('wishes.togglePin');
    Route::delete('/wishes/{wish}', [DashboardController::class, 'destroyWish'])->name('wishes.destroy');
});
