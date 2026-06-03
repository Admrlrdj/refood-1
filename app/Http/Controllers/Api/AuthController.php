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
            'username' => 'required|string|max:50|unique:donors', // Username wajib & unik
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
            'username' => strtolower(str_replace(' ', '', $request->username)), // Pastikan kecil & tanpa spasi
            'restaurant_name' => $request->restaurant_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'type' => 'general',
            'is_verified' => false,
            'status' => 'offline',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Registrasi Donatur berhasil!'], 201);
    }

    // Register Penerima
    public function registerReceiver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'username'        => 'required|string|max:255|unique:receivers,username',
            'type'            => 'required|string', // misal: Panti Asuhan, Komunitas
            'pic_name'        => 'required|string|max:255',
            'phone'           => 'required|string',
            'email'           => 'required|string|email|max:255|unique:receivers,email',
            'address'         => 'required|string',
            'capacity_people' => 'required|integer|min:1',
            'need_level'      => 'required|string', // misal: Tinggi, Sedang, Rendah
            'password'        => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $receiver = Receiver::create([
            'name'            => $request->name,
            'username'        => $request->username,
            'type'            => $request->type,
            'pic_name'        => $request->pic_name,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'address'         => $request->address,
            'capacity_people' => $request->capacity_people,
            'need_level'      => $request->need_level,
            'password'        => Hash::make($request->password),
        ]);

        $token = $receiver->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'message'      => 'Registrasi Penerima Berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'data'         => $receiver
        ], 201);
    }

    public function registerVolunteer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:volunteers',
            'email' => 'required|string|email|max:255|unique:volunteers',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $volunteer = Volunteer::create([
            'name' => $request->name,
            'username' => strtolower(str_replace(' ', '', $request->username)),
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type ?? '',
            'vehicle_plate' => $request->vehicle_plate ?? '',
            'address' => $request->address ?? '',
            'password' => Hash::make($request->password),
            'is_verified' => false,
            'status' => 'offline',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Registrasi Relawan berhasil!'], 201);
    }

    // ==========================================
    // 2. LOGIN (MENGGUNAKAN USERNAME)
    // ==========================================
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string', // Ubah dari email ke username
            'password' => 'required',
            'role' => 'required|in:donor,receiver,volunteer'
        ]);

        $user = null;
        $usernameInput = strtolower(str_replace(' ', '', $request->username)); // Bersihkan input

        if ($request->role === 'donor') {
            $user = Donor::where('username', $usernameInput)->first();
        } elseif ($request->role === 'receiver') {
            $user = Receiver::where('username', $usernameInput)->first();
        } elseif ($request->role === 'volunteer') {
            $user = Volunteer::where('username', $usernameInput)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Username atau Password salah.'], 401);
        }

        if (!$user->is_verified) {
            return response()->json(['status' => 'error', 'message' => 'Akun Anda sedang ditinjau oleh Admin.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'access_token' => $token,
            'data' => $user
        ], 200);
    }

    // ==========================================
    // 3. LOGOUT
    // ==========================================
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => 'Logout berhasil'], 200);
    }
}
