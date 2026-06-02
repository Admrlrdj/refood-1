<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Donor;
use App\Models\Receiver;
use App\Models\Volunteer;

class AuthController extends Controller
{
    // ==========================================
    // 1. REGISTRASI
    // ==========================================
    public function registerDonor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'restaurant_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:donors',
            'phone' => 'required|string',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $donor = Donor::create([
            'name' => $request->name,
            'restaurant_name' => $request->restaurant_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'type' => 'general',
            'is_verified' => false, // Menunggu Verifikasi Admin
            'status' => 'offline',
            'last_latitude' => null,
            'last_longitude' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi Donatur berhasil! Silakan tunggu verifikasi admin.',
            'data' => $donor
        ], 201);
    }

    public function registerReceiver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:receivers',
            'phone' => 'required|string',
            'capacity_people' => 'nullable|integer',
            'need_level' => 'nullable|integer|min:0|max:100',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $receiver = Receiver::create([
            'name' => $request->name,
            'pic_name' => $request->pic_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'capacity_people' => $request->capacity_people ?? 0,
            'need_level' => $request->need_level ?? 0,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'type' => 'foundation',
            'is_verified' => false,
            'status' => 'offline',
            'last_latitude' => null,
            'last_longitude' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi Penerima berhasil! Silakan tunggu verifikasi admin.',
            'data' => $receiver
        ], 201);
    }

    public function registerVolunteer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:volunteers',
            'phone' => 'required|string',
            'vehicle_type' => 'required|string',
            'vehicle_plate' => 'required|string',
            'address' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $username = strtolower(str_replace(' ', '_', $request->name)) . '_' . rand(100, 999);

        $volunteer = Volunteer::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plate' => $request->vehicle_plate,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'is_verified' => false,
            'status' => 'offline',
            'last_latitude' => null,
            'last_longitude' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi Relawan berhasil! Silakan tunggu verifikasi admin.',
            'data' => $volunteer
        ], 201);
    }

    // ==========================================
    // 2. LOGIN (Multi Role)
    // ==========================================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:donor,receiver,volunteer' // Wajib mengirimkan role dari Flutter
        ]);

        $user = null;

        // Cari user di collection yang sesuai dengan role
        if ($request->role === 'donor') {
            $user = Donor::where('email', $request->email)->first();
        } elseif ($request->role === 'receiver') {
            $user = Receiver::where('email', $request->email)->first();
        } elseif ($request->role === 'volunteer') {
            $user = Volunteer::where('email', $request->email)->first();
        }

        // Cek apakah user ada dan password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau Password salah.'
            ], 401);
        }

        // Cek Verifikasi Admin
        if (!$user->is_verified) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda sedang ditinjau. Harap tunggu verifikasi dari Admin.'
            ], 403);
        }

        // Buat Sanctum Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user
        ], 200);
    }

    // ==========================================
    // 3. LOGOUT
    // ==========================================
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ], 200);
    }
}
