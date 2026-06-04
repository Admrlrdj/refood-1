<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;

class ReceiverController extends Controller
{
    // ==========================================
    // 1. GET DASHBOARD PENERIMA (YAYASAN)
    // ==========================================
    public function dashboard(Request $request)
    {
        $receiverId = (string) $request->user()->_id;

        // 1. MAKANAN MASUK TERKINI (Donasi dari donatur yang siap diklaim)
        // Logika: Status 'available' dan belum ada receiver_id yang klaim
        $incomingFoods = Food::with('donor')
            ->where('status', 'available')
            ->where(function ($query) {
                $query->whereNull('receiver_id')
                    ->orWhere('receiver_id', '');
            })
            ->orderBy('created_at', 'desc')
            ->take(10) // Tampilkan 10 terbaru di beranda
            ->get();

        // 2. REQUEST AKTIF ANDA (Request makanan yang diajukan yayasan ini)
        $activeRequests = Food::where('receiver_id', $receiverId)
            ->whereIn('status', ['waiting_donor', 'requested'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. MENGHITUNG SUMMARY (Untuk 3 Card di atas)
        $receivedCount = Food::where('receiver_id', $receiverId)
            ->where('status', 'completed')
            ->count();

        $onDeliveryCount = Food::where('receiver_id', $receiverId)
            ->whereIn('status', ['accepted', 'on_delivery'])
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'incoming_foods' => $incomingFoods, // Sekarang berisi donasi yang available!
                'active_requests' => $activeRequests,
                'summary' => [
                    'received' => $receivedCount,
                    'on_delivery' => $onDeliveryCount,
                    'requests' => $activeRequests->count(),
                ]
            ]
        ], 200);
    }

    // ==========================================
    // 2. GET DETAIL MAKANAN / REQUEST
    // ==========================================
    public function getFoodDetail(Request $request, $id)
    {
        // PENTING: Gunakan find($id) agar string ID otomatis jadi ObjectID MongoDB
        $food = Food::with(['donor', 'volunteer', 'receiver'])->find($id);

        if (!$food) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan / ditarik oleh donatur.'
            ], 404);
        }

        return response()->json(['status' => 'success', 'data' => $food], 200);
    }

    // ==========================================
    // 3. BUAT REQUEST MAKANAN BARU
    // ==========================================
    public function createRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'portion' => 'required',
        ]);

        $food = Food::create([
            'receiver_id' => (string) $request->user()->_id,
            'name' => $request->name,
            'category' => $request->category ?? 'Umum',
            'portion' => (string) $request->portion,
            'note' => $request->note ?? '',
            'status' => 'waiting_donor', // Status awal request SESUAI DB kamu
            'donor_id' => null,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Request makanan berhasil diajukan!', 'data' => $food], 201);
    }

    // ==========================================
    // 4. BATALKAN REQUEST
    // ==========================================
    public function deleteRequest(Request $request, $id)
    {
        $food = Food::find($id);

        if ($food && (string) $food->receiver_id === (string) $request->user()->_id) {
            $food->delete();
            return response()->json(['status' => 'success', 'message' => 'Request berhasil dibatalkan.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Gagal membatalkan request.'], 400);
    }

    // ==========================================
    // FITUR TAMBAHAN (CARI & TERIMA MAKANAN)
    // ==========================================
    public function getAvailableFoods(Request $request)
    {
        $foods = Food::with('donor')
            ->where('status', 'available')
            ->whereNull('receiver_id')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['status' => 'success', 'data' => $foods], 200);
    }

    public function acceptDonation(Request $request, $id)
    {
        $food = Food::find($id);
        if (!$food || $food->status !== 'available') return response()->json(['status' => 'error', 'message' => 'Donasi tidak tersedia'], 400);
        $food->receiver_id = (string) $request->user()->_id;
        $food->status = 'accepted';
        $food->save();
        return response()->json(['status' => 'success', 'message' => 'Donasi berhasil diterima'], 200);
    }

    public function updateRequest(Request $request, $id)
    { /* Opsional */
    }

    // ==========================================
    // 5. HISTORY, PROFILE & SETTINGS
    // ==========================================
    public function history(Request $request)
    {
        $receiverId = (string) $request->user()->_id;
        $history = Food::where('receiver_id', $receiverId)->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $history], 200);
    }

    public function getProfile(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => $request->user()], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $user->update($request->only(['name', 'pic_name', 'phone', 'address', 'capacity_people', 'need_level']));
        return response()->json(['status' => 'success', 'message' => 'Profil berhasil diperbarui']);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();
        if ($request->has('username')) $user->username = $request->username;
        if ($request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) return response()->json(['status' => 'error', 'message' => 'Password lama salah'], 400);
            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'Pengaturan disimpan']);
    }
}
