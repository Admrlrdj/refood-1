<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DonorController extends Controller
{
    // ==========================================
    // 1. GET DASHBOARD STATS & RECENT ACTIVITY
    // ==========================================
    public function dashboard(Request $request)
    {
        $donorId = $request->user()->_id;

        // Menghitung statistik berdasarkan status makanan
        // 'available' = Menunggu Donasi (belum diambil)
        // 'on_delivery' / 'accepted' = Donasi Aktif (sedang diproses kurir)
        // 'completed' / 'cancelled' = Riwayat (sudah selesai/gagal)

        $menungguDonasi = Food::where('donor_id', $donorId)->where('status', 'available')->count();
        $donasiAktif = Food::where('donor_id', $donorId)->whereIn('status', ['accepted', 'on_delivery'])->count();
        $riwayat = Food::where('donor_id', $donorId)->whereIn('status', ['completed', 'cancelled'])->count();

        // Mengambil 3 aktivitas terbaru
        $recentActivities = Food::where('donor_id', $donorId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'waiting' => $menungguDonasi,
                    'active' => $donasiAktif,
                    'history' => $riwayat,
                ],
                'recent_activities' => $recentActivities
            ]
        ], 200);
    }

    // ==========================================
    // 2. CREATE DONASI MAKANAN (POST)
    // ==========================================
    public function createDonation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'portion' => 'required', // Tetap diterima sebagai apapun, akan kita cast ke string
            'collection_date' => 'required|date',
            'note' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/foods');
            $photoUrl = str_replace('public/', 'storage/', $path);
        }

        // Simpan data sesuai struktur yang kamu minta
        $food = Food::create([
            'name' => $request->name,
            'category' => $request->category,
            'portion' => (string) $request->portion, // Disimpan sebagai string seperti contoh "1"
            'donor_id' => $request->user()->_id, // ID Donatur yang sedang login
            'receiver_id' => null, // Masih null karena belum ada penerima yang request
            'status' => 'available', // Status awal saat baru diposting
            'collection_date' => \Carbon\Carbon::parse($request->collection_date), // Akan tersimpan sebagai ISODate di MongoDB
            'note' => $request->note,
            'photo_url' => $photoUrl,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Donasi makanan berhasil ditambahkan!',
            'data' => $food
        ], 201);
    }

    // ==========================================
    // 3. GET RIWAYAT DONASI
    // ==========================================
    public function history(Request $request)
    {
        $donorId = $request->user()->_id;

        // Mengambil semua data donasi milik user ini, diurutkan dari yang terbaru
        $foods = Food::where('donor_id', $donorId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $foods
        ], 200);
    }

    // ==========================================
    // 4. CRUD PROFILE (VIEW & UPDATE)
    // ==========================================
    public function getProfile(Request $request)
    {
        // Mengembalikan data user yang sedang login
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $donor = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'restaurant_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // Update hanya data yang dikirim dari Flutter
        if ($request->has('name')) $donor->name = $request->name;
        if ($request->has('restaurant_name')) $donor->restaurant_name = $request->restaurant_name;
        if ($request->has('phone')) $donor->phone = $request->phone;
        if ($request->has('address')) $donor->address = $request->address;

        $donor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui!',
            'data' => $donor
        ], 200);
    }
}
