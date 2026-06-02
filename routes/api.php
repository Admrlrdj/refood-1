<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

    // Endpoint untuk mengecek profil user yang sedang login saat ini
    Route::get('/profile', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    });

    // TODO: Nanti endpoint aplikasi (seperti Create Food, Get Deliveries, dll) 
    // ditaruh di dalam grup middleware ini agar aman.

});
