<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;

class DonorController extends Controller
{
    // ==========================================
    // 1. GET DASHBOARD (Statistik, Donasi Aktif, & Request Yayasan)
    // ==========================================
    public function dashboard(Request $request)
    {
        $donorId = (string) $request->user()->_id;

        // A. Ambil Donasi Aktif (Status: available, accepted, on_delivery)
        $activeDonationsList = Food::where('donor_id', $donorId)
            ->whereIn('status', ['available', 'accepted', 'on_delivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        // B. Ambil Request Yayasan (Status: waiting_donor)
        $yayasanRequests = Food::with('receiver')
            ->where('status', 'waiting_donor')
            ->where(function ($query) {
                $query->whereNull('donor_id')
                    ->orWhere('donor_id', '')
                    ->orWhereExists('donor_id', false);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $activeDonationsCount = $activeDonationsList->count();
        $completedDonationsCount = Food::where('donor_id', $donorId)
            ->where('status', 'completed')
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'active' => $activeDonationsCount,
                    'completed' => $completedDonationsCount,
                ],
                'active_donations' => $activeDonationsList,
                'yayasan_requests' => $yayasanRequests
            ]
        ], 200);
    }

    // ==========================================
    // 2. GET DETAIL DONASI
    // ==========================================
    public function getDonation(Request $request, $id)
    {
        $food = Food::where('_id', $id)->first();

        if (!$food || (string) $food->donor_id !== (string) $request->user()->_id) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $food], 200);
    }

    // ==========================================
    // 3. PENUHI REQUEST YAYASAN
    // ==========================================
    public function fulfillRequest(Request $request, $id)
    {
        $food = Food::where('_id', $id)->first();

        if (!$food || $food->status !== 'waiting_donor') {
            return response()->json(['status' => 'error', 'message' => 'Permintaan ini sudah dipenuhi donatur lain.'], 400);
        }

        $food->donor_id = (string) $request->user()->_id;
        $food->status = 'accepted';
        $food->save();

        return response()->json(['status' => 'success', 'message' => 'Berhasil memenuhi permintaan!'], 200);
    }

    // ==========================================
    // 4. BUAT DONASI BARU
    // ==========================================
    public function createDonation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'portion' => 'required', // Menyesuaikan input string/integer dari frontend
        ]);

        $food = Food::create([
            'donor_id' => (string) $request->user()->_id,
            'name' => $request->name,
            'category' => $request->category ?? 'Umum',
            'portion' => (string) $request->portion,
            'note' => $request->note ?? '',
            'photo_url' => $request->photo_url ?? null,
            'collection_date' => $request->collection_date ?? null,
            'status' => 'available', // Status awal donasi baru
        ]);

        return response()->json(['status' => 'success', 'message' => 'Donasi berhasil dibuat', 'data' => $food], 201);
    }

    public function updateDonation(Request $request, $id)
    { /* opsional */
    }

    // ==========================================
    // 5. BATALKAN/HAPUS DONASI
    // ==========================================
    public function deleteDonation(Request $request, $id)
    {
        $food = Food::where('_id', $id)->first();
        if ($food && (string) $food->donor_id === (string) $request->user()->_id) {
            $food->delete();
            return response()->json(['status' => 'success', 'message' => 'Donasi dibatalkan.']);
        }
        return response()->json(['status' => 'error', 'message' => 'Gagal menghapus.'], 400);
    }

    // ==========================================
    // 6. HISTORY, PROFILE & SETTINGS
    // ==========================================
    public function history(Request $request)
    {
        $donorId = (string) $request->user()->_id;
        $history = Food::where('donor_id', $donorId)->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $history], 200);
    }

    public function getProfile(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => $request->user()], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $user->update($request->only(['name', 'restaurant_name', 'phone', 'address']));
        return response()->json(['status' => 'success', 'message' => 'Profil diperbarui']);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();
        if ($request->has('username')) $user->username = strtolower(str_replace(' ', '', $request->username));
        if ($request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) return response()->json(['status' => 'error', 'message' => 'Password lama salah'], 400);
            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'Pengaturan disimpan']);
    }
}
