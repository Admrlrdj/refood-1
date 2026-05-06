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
        Route::resource('foods', FoodController::class);
        Route::patch('/foods/{food}/mark-invalid', [FoodController::class, 'markInvalid'])->name('foods.mark-invalid');

        // Deliveries
        Route::resource('deliveries', DeliveryController::class);
        Route::patch('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
     

        // Donors
        Route::resource('donors', DonorController::class);
        Route::get('/donors/{donor}/history', [DonorController::class, 'history'])->name('donors.history');
     
        // Receivers
        Route::resource('receivers', ReceiverController::class);
        Route::get('/receivers/{receiver}/json',   [ReceiverController::class, 'json'])  ->name('receivers.json');
        Route::get('/receivers/{receiver}/detail', [ReceiverController::class, 'detail'])->name('receivers.detail');
        // Volunteers
        Route::resource('volunteers', VolunteerController::class);

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
