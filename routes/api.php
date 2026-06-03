<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DonorController;

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

    Route::prefix('donor')->group(function () {
        Route::get('/dashboard', [DonorController::class, 'dashboard']);
        Route::post('/foods', [DonorController::class, 'createDonation']);
        Route::get('/history', [DonorController::class, 'history']);

        Route::get('/profile', [DonorController::class, 'getProfile']);
        Route::put('/profile', [DonorController::class, 'updateProfile']);
    });
});
