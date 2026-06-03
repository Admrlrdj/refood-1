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
// PUBLIC ROUTES (Tidak butuh Token)
// ==========================================
Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/receiver', [AuthController::class, 'registerReceiver']);
Route::post('/register/volunteer', [AuthController::class, 'registerVolunteer']);
Route::post('/login', [AuthController::class, 'login']);


// ==========================================
// PROTECTED ROUTES (Butuh Token Bearer dari Sanctum)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Endpoint untuk Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Group Route untuk Donatur
    Route::prefix('donor')->group(function () {
        Route::get('/dashboard', [DonorController::class, 'dashboard']);
        Route::post('/foods', [DonorController::class, 'createDonation']);
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

    // Group Route untuk Relawan
    Route::prefix('volunteer')->group(function () {
        Route::get('/dashboard', [VolunteerController::class, 'dashboard']);
        Route::get('/profile', [VolunteerController::class, 'getProfile']);
    });
});
