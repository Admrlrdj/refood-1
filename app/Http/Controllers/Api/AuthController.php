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
    // 1. REGISTRASI

    // Register Donatur
    public function registerDonor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:donors',
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
            'username' => strtolower(str_replace(' ', '', $request->username)),
            'restaurant_name' => $request->restaurant_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'type' => 'general',
            'is_verified' => true, // Set true sementara agar bisa langsung login
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
            'type'            => 'required|string',
            'pic_name'        => 'required|string|max:255',
            'phone'           => 'required|string',
            'email'           => 'required|string|email|max:255|unique:receivers,email',
            'address'         => 'required|string',
            'capacity_people' => 'required|integer|min:1',
            'need_level'      => 'required|string',
            'password'        => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
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
            'is_verified'     => true, // Set true sementara agar bisa langsung login
        ]);

        return response()->json(['status' => 'success', 'message' => 'Registrasi Penerima Berhasil'], 201);
    }

    // Register Relawan (Volunteer) Tanpa Collection User
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
            'address' => $request->address ?? '',
            'vehicle_type' => $request->vehicle_type ?? '',
            'vehicle_plate' => $request->vehicle_plate ?? '',
            'password' => Hash::make($request->password),
            'verification_status' => 'verified',
            'is_online' => false,
            'is_verified' => true,
            'status' => 'offline',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Registrasi Relawan berhasil!'], 201);
    }

    // 2. LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
            'role' => 'required|in:donor,receiver,volunteer'
        ]);

        $user = null;

        if ($request->role === 'donor') {
            $usernameInput = strtolower(str_replace(' ', '', $request->username));
            $user = Donor::where('username', $usernameInput)->first();
        } elseif ($request->role === 'receiver') {
            $user = Receiver::where('username', $request->username)->first();
        } elseif ($request->role === 'volunteer') {
            $usernameInput = strtolower(str_replace(' ', '', $request->username));
            $user = Volunteer::where('username', $usernameInput)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Username atau Password salah.'], 401);
        }

        $isVerified = $user->is_verified ?? true;
        if (!$isVerified) {
            return response()->json(['status' => 'error', 'message' => 'Akun Anda sedang ditinjau oleh Admin.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $userData = $user->toArray();
        $userData['role'] = $request->role;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $userData,
            'data' => $user
        ], 200);
    }

    // 3. LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => 'Logout berhasil'], 200);
    }
}
