<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DonorController;
use App\Http\Controllers\Api\ReceiverController;
use App\Http\Controllers\Api\VolunteerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/receiver', [AuthController::class, 'registerReceiver']);
Route::post('/register/volunteer', [AuthController::class, 'registerVolunteer']);
Route::post('/login', [AuthController::class, 'login']);


// ==========================================
// PROTECTED ROUTES
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Endpoint Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Group Route Donatur
    Route::prefix('donor')->group(function () {
        Route::get('/dashboard', [DonorController::class, 'dashboard']);
        Route::post('/foods', [DonorController::class, 'createDonation']);
        Route::post('/foods/{id}', [DonorController::class, 'updateDonation']);

        // Riwayat (HISTORY)
        Route::get('/history', [DonorController::class, 'history']);

        Route::get('/profile', [DonorController::class, 'getProfile']);
        Route::put('/profile', [DonorController::class, 'updateProfile']);
        Route::put('/settings', [DonorController::class, 'updateSettings']);

        Route::get('/foods/{id}', [DonorController::class, 'getDonation']);
        Route::post('/foods/{id}', [DonorController::class, 'updateDonation']);
        Route::delete('/foods/{id}', [DonorController::class, 'deleteDonation']);
    });

    // Group Route untuk Penerima
    Route::prefix('receiver')->group(function () {
        Route::get('/dashboard', [ReceiverController::class, 'dashboard']);

        // Fitur Cari & Request Makanan
        Route::get('/foods/available', [ReceiverController::class, 'getAvailableFoods']);
        Route::post('/foods/request', [ReceiverController::class, 'createRequest']);

        // Riwayat (HISTORY)
        Route::get('/history', [ReceiverController::class, 'history']);

        // Profile & Settings
        Route::get('/profile', [ReceiverController::class, 'getProfile']);
        Route::put('/profile', [ReceiverController::class, 'updateProfile']);
        Route::put('/settings', [ReceiverController::class, 'updateSettings']);

        // Detail & Aksi untuk Makanan yang di-Request
        Route::get('/foods/{id}', [ReceiverController::class, 'getFoodDetail']);
        Route::post('/foods/{id}/accept', [ReceiverController::class, 'acceptDonation']);
        Route::post('/foods/request/{id}', [ReceiverController::class, 'updateRequest']);
        Route::delete('/foods/request/{id}', [ReceiverController::class, 'deleteRequest']);
    });

    // Group Route untuk Relawan (Volunteer)
    Route::prefix('volunteer')->group(function () {
        Route::get('/dashboard', [VolunteerController::class, 'dashboard']);

        // Riwayat Pengantaran (HISTORY)
        Route::get('/history', [VolunteerController::class, 'history']);

        // Profile & Settings
        Route::get('/profile', [VolunteerController::class, 'getProfile']);
        Route::put('/profile', [VolunteerController::class, 'updateProfile']);
        Route::put('/settings', [VolunteerController::class, 'updateSettings']);

        // Detail & Aksi untuk Tugas Pengantaran (Jobs)
        Route::get('/jobs/{id}', [VolunteerController::class, 'getJobDetail']);
        Route::post('/jobs/{id}/accept', [VolunteerController::class, 'acceptJob']);
        Route::post('/jobs/{id}/status', [VolunteerController::class, 'updateJobStatus']);
    });
});
