<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DonorController extends Controller
{
    // 1. GET DASHBOARD STATS & RECENT ACTIVITY
    public function dashboard(Request $request)
    {
        $donorId = $request->user()->_id;

        // A. Ambil Donasi Aktif milik Donatur ini
        $activeDonationsList = \App\Models\Food::where('donor_id', $donorId)
            ->whereIn('status', ['pending', 'accepted', 'on_delivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        $activeDonationsCount = $activeDonationsList->count();

        $completedDonationsCount = \App\Models\Food::where('donor_id', $donorId)
            ->where('status', 'completed')
            ->count();

        // B. Ambil Request Makanan dari Yayasan (Receiver) yang belum ada donaturnya
        // Syarat: statusnya 'requested' dan belum ada donor_id
        $yayasanRequests = \App\Models\Food::with('receiver')
            ->where('status', 'requested')
            ->whereNull('donor_id')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'active' => $activeDonationsCount,
                    'completed' => $completedDonationsCount,
                ],
                'active_donations' => $activeDonationsList, // <-- Ini yang hilang kemarin
                'yayasan_requests' => $yayasanRequests
            ]
        ], 200);
    }

    // 2. CREATE DONASI MAKANAN (POST)
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

    // 3. GET RIWAYAT DONASI
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

    // 4. CRUD PROFILE (VIEW & UPDATE)
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

    // 5. UPDATE SETTINGS (USERNAME & PASSWORD)
    public function updateSettings(Request $request)
    {
        $donor = $request->user();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'username' => 'sometimes|string|max:50',
            'old_password' => 'required_with:new_password|string',
            'new_password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // 1. Cek & Update Username
        if ($request->has('username') && $request->username !== $donor->username) {
            $newUsername = strtolower(str_replace(' ', '', $request->username));

            // Pengecekan manual apakah username sudah dipakai orang lain
            $exists = \App\Models\Donor::where('username', $newUsername)
                ->where('_id', '!=', $donor->_id)
                ->exists();
            if ($exists) {
                return response()->json(['status' => 'error', 'message' => 'Username sudah digunakan pengguna lain.'], 422);
            }
            $donor->username = $newUsername;
        }

        // 2. Cek & Update Password
        if ($request->has('new_password') && !empty($request->new_password)) {
            // Pastikan password lama yang dimasukkan benar
            if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $donor->password)) {
                return response()->json(['status' => 'error', 'message' => 'Password lama salah!'], 400);
            }
            $donor->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $donor->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaturan akun berhasil diperbarui!',
            'data' => $donor
        ], 200);
    }

    // 6. GET DETAIL DONASI
    public function getDonation(Request $request, $id)
    {
        $food = \App\Models\Food::find($id);

        // Membandingkan ID dengan aman (dijadikan string agar terhindar dari error ObjectId MongoDB)
        if (!$food || (string) $food->donor_id !== (string) $request->user()->_id) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $food], 200);
    }

    // 7. UPDATE DONASI (EDIT)
    public function updateDonation(Request $request, $id)
    {
        $food = \App\Models\Food::find($id);

        if (!$food || (string) $food->donor_id !== (string) $request->user()->_id) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'portion' => 'sometimes|required',
            'collection_date' => 'sometimes|required|date',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // Ganti foto lama jika ada upload baru
        if ($request->hasFile('photo')) {
            if ($food->photo_url) {
                \Illuminate\Support\Facades\Storage::delete(str_replace('storage/', 'public/', $food->photo_url));
            }
            $path = $request->file('photo')->store('public/foods');
            $food->photo_url = str_replace('public/', 'storage/', $path);
        }

        if ($request->has('name')) $food->name = $request->name;
        if ($request->has('category')) $food->category = $request->category;
        if ($request->has('portion')) $food->portion = (string) $request->portion;
        if ($request->has('collection_date')) $food->collection_date = \Carbon\Carbon::parse($request->collection_date);
        if ($request->has('note')) $food->note = $request->note;

        $food->save();

        return response()->json(['status' => 'success', 'message' => 'Donasi berhasil diperbarui', 'data' => $food], 200);
    }

    // 8. DELETE DONASI
    public function deleteDonation(Request $request, $id)
    {
        $food = \App\Models\Food::find($id);

        if (!$food || (string) $food->donor_id !== (string) $request->user()->_id) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        // Hapus file gambar dari server
        if ($food->photo_url) {
            \Illuminate\Support\Facades\Storage::delete(str_replace('storage/', 'public/', $food->photo_url));
        }

        $food->delete();

        return response()->json(['status' => 'success', 'message' => 'Donasi berhasil dihapus'], 200);
    }
}
