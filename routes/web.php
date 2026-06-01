<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\DonorController;
use App\Http\Controllers\Admin\ReceiverController;
use App\Http\Controllers\Admin\VolunteerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;

// ─────────────────────────────────────────
//  Landing Page
// ─────────────────────────────────────────
Route::get('/', [AdminAuthController::class, 'home'])->name('home');

// ─────────────────────────────────────────
//  Admin Routes
// ─────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest-only
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth:admin');

    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Foods
        Route::resource('foods', FoodController::class)->except(['create', 'store']);
        Route::patch('/foods/{food}/mark-invalid', [FoodController::class, 'markInvalid'])->name('foods.mark-invalid');

        // Deliveries
        Route::resource('deliveries', DeliveryController::class)->except(['create', 'store']);
        Route::patch('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('deliveries.update-status');


        // ----------------- Donors -----------------
        Route::resource('donors', DonorController::class)->except(['create', 'store']);
        Route::get('/donors/{id}/history', [DonorController::class, 'history'])->name('donors.history');
        Route::post('/donors/{id}/verify', [DonorController::class, 'verify'])->name('donors.verify');
        Route::post('/donors/{id}/reject', [DonorController::class, 'reject'])->name('donors.reject');

        // ----------------- Receivers -----------------
        Route::resource('receivers', ReceiverController::class)->except(['create', 'store']);
        Route::get('/receivers/{id}/json',   [ReceiverController::class, 'json'])->name('receivers.json');
        Route::get('/receivers/{id}/detail', [ReceiverController::class, 'detail'])->name('receivers.detail');
        Route::post('/receivers/{id}/verify', [ReceiverController::class, 'verify'])->name('receivers.verify');
        Route::post('/receivers/{id}/reject', [ReceiverController::class, 'reject'])->name('receivers.reject');

        // ----------------- Volunteers -----------------
        Route::resource('volunteers', VolunteerController::class)->except(['create', 'store']);
        Route::post('/volunteers/{id}/verify', [VolunteerController::class, 'verify'])->name('volunteers.verify');
        Route::post('/volunteers/{id}/reject', [VolunteerController::class, 'reject'])->name('volunteers.reject');


        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

        // Forgot Password
        Route::get('/forgot-password',  [AdminAuthController::class, 'showForgotPassword'])->name('admin.forgot-password');
        Route::post('/forgot-password', [AdminAuthController::class, 'resetPassword'])->name('admin.forgot-password.post');
    });
});
