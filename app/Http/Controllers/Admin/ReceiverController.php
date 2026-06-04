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

        // Makanan Masuk (Pasti memiliki donor_id dan status accepted/on_delivery)
        $incomingFoods = \App\Models\Food::with('donor')
            ->where('receiver_id', $receiverId)
            ->whereNotNull('donor_id')
            ->whereIn('status', ['accepted', 'on_delivery'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Request Mandiri (Status WAJIB waiting_donor atau requested)
        $activeRequests = \App\Models\Food::where('receiver_id', $receiverId)
            ->whereIn('status', ['waiting_donor', 'requested'])
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
    // 2. GET AVAILABLE FOODS (FIX BUG 3: Method ini sebelumnya tidak ada)
    // ==========================================
    public function getAvailableFoods(Request $request)
    {
        $query = Food::with('donor')
            ->whereNotNull('donor_id')
            ->whereNull('receiver_id')
            ->where('status', 'available')
            ->orderBy('created_at', 'desc');

        // Support pencarian nama
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->get();

        return response()->json(['status' => 'success', 'data' => $foods], 200);
    }

    // ==========================================
    // 3. GET DETAIL MAKANAN / REQUEST
    // ==========================================
    public function getFoodDetail(Request $request, $id)
    {
        $food = Food::with(['donor', 'volunteer', 'receiver'])->find($id);

        if (!$food) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan / ditarik oleh donatur.'
            ], 404);
        }

        return response()->json(['status' => 'success', 'data' => $food], 200);
    }

    // ==========================================
    // 4. ACCEPT DONATION (FIX BUG 4: Method ini sebelumnya tidak ada)
    // ==========================================
    public function acceptDonation(Request $request, $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        if ($food->receiver_id !== null) {
            return response()->json(['status' => 'error', 'message' => 'Donasi ini sudah diklaim receiver lain.'], 400);
        }

        if ($food->status !== 'available') {
            return response()->json(['status' => 'error', 'message' => 'Donasi ini tidak tersedia.'], 400);
        }

        $food->receiver_id = (string) $request->user()->_id;
        $food->status      = 'accepted';
        $food->save();

        return response()->json(['status' => 'success', 'message' => 'Berhasil menerima donasi!', 'data' => $food], 200);
    }

    // ==========================================
    // 5. BUAT REQUEST MAKANAN BARU
    // ==========================================
    public function createRequest(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'portion' => 'required',
        ]);

        $food = Food::create([
            'receiver_id'     => (string) $request->user()->_id,
            'name'            => $request->name,
            'category'        => $request->category ?? 'Umum',
            'portion'         => (string) $request->portion,
            'note'            => $request->note ?? '',
            'status'          => 'waiting_donor',
            'donor_id'        => null,
            'collection_date' => $request->collection_date ?? null,
            'photo_url'       => null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Request makanan berhasil diajukan!',
            'data'    => $food
        ], 201);
    }

    // ==========================================
    // 6. UPDATE REQUEST
    // ==========================================
    public function updateRequest(Request $request, $id)
    {
        $food = Food::find($id);

        if (!$food || (string) $food->receiver_id !== (string) $request->user()->_id) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        // Hanya boleh edit kalau donor belum ada
        if ($food->donor_id !== null) {
            return response()->json(['status' => 'error', 'message' => 'Request sudah diambil donatur, tidak bisa diedit.'], 400);
        }

        $food->update($request->only(['name', 'category', 'portion', 'note', 'collection_date']));

        return response()->json(['status' => 'success', 'message' => 'Request berhasil diperbarui.', 'data' => $food], 200);
    }

    // ==========================================
    // 7. BATALKAN REQUEST
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
    // 8. HISTORY
    // ==========================================
    public function history(Request $request)
    {
        $history = Food::where('receiver_id', (string) $request->user()->_id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $history], 200);
    }

    // ==========================================
    // 9. PROFILE
    // ==========================================
    public function getProfile(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => $request->user()], 200);
    }

    public function updateProfile(Request $request)
    {
        $request->user()->update($request->only(['name', 'pic_name', 'phone', 'address', 'capacity_people', 'need_level']));
        return response()->json(['status' => 'success', 'message' => 'Profil berhasil diperbarui']);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();
        if ($request->has('username')) $user->username = $request->username;
        if ($request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) {
                return response()->json(['status' => 'error', 'message' => 'Password lama salah'], 400);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'Pengaturan disimpan']);
    }
}
