<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;

class ReceiverController extends Controller
{
    // ==========================================
    // 1. GET DASHBOARD
    // ==========================================
    public function dashboard(Request $request)
    {
        $receiverId = (string) $request->user()->_id;

        // Makanan Masuk (Telah di-accept oleh relawan/donatur)
        $incomingFoods = Food::with('donor')
            ->where('receiver_id', $receiverId)
            ->whereIn('status', ['accepted', 'on_delivery'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Request Mandiri (Berstatus waiting_donor)
        $activeRequests = Food::where('receiver_id', $receiverId)
            ->where('status', 'waiting_donor')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'incoming_foods' => $incomingFoods,
                'active_requests' => $activeRequests,
            ]
        ], 200);
    }

    // ==========================================
    // 2. GET DETAIL MAKANAN / REQUEST
    // ==========================================
    public function getFoodDetail(Request $request, $id)
    {
        $food = Food::with(['donor', 'volunteer', 'receiver'])->where('_id', $id)->first();

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
            'status' => 'waiting_donor', // Status awal request
            'donor_id' => null,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Request makanan berhasil diajukan!', 'data' => $food], 201);
    }

    // ==========================================
    // 4. BATALKAN REQUEST
    // ==========================================
    public function deleteRequest(Request $request, $id)
    {
        $food = Food::where('_id', $id)->first();

        if ($food && (string) $food->receiver_id === (string) $request->user()->_id) {
            $food->delete();
            return response()->json(['status' => 'success', 'message' => 'Request berhasil dibatalkan.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Gagal membatalkan request.'], 400);
    }

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
