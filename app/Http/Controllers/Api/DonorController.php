<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use MongoDB\BSON\ObjectId;

class DonorController extends Controller
{
    // ==========================================
    // 1. GET DASHBOARD DONATUR
    // ==========================================
    public function dashboard(Request $request)
    {
        $donorId = (string) $request->user()->_id;

        $activeDonationsList = Food::where('donor_id', $donorId)
            ->whereIn('status', ['available', 'pending', 'accepted', 'on_delivery', 'invalid'])
            ->orderBy('created_at', 'desc')
            ->get();

        $yayasanRequests = Food::with('receiver')
            ->whereIn('status', ['waiting_donor', 'requested'])
            ->orderBy('created_at', 'desc')
            ->take(15)
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
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if (!$food) return response()->json(['status' => 'error', 'message' => 'Data donasi tidak ditemukan.'], 404);
            if ((string) $food->donor_id !== (string) $request->user()->_id) return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki akses.'], 403);

            return response()->json(['status' => 'success', 'data' => $food], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    // ==========================================
    // 3. PENUHI REQUEST YAYASAN
    // ==========================================
    public function fulfillRequest(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if (!$food || !in_array($food->status, ['waiting_donor', 'requested'])) return response()->json(['status' => 'error', 'message' => 'Permintaan sudah dipenuhi.'], 400);

            $food->donor_id = (string) $request->user()->_id;
            $food->status = 'accepted';
            $food->save();

            return response()->json(['status' => 'success', 'message' => 'Berhasil memenuhi permintaan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    // ==========================================
    // 4. BUAT DONASI BARU
    // ==========================================
    public function createDonation(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'portion' => 'required']);
        $food = Food::create([
            'donor_id' => (string) $request->user()->_id,
            'name' => $request->name,
            'category' => $request->category ?? 'Umum',
            'portion' => (string) $request->portion,
            'note' => $request->note ?? '',
            'photo_url' => $request->photo_url ?? null,
            'collection_date' => $request->collection_date ?? null,
            'status' => 'available',
        ]);
        return response()->json(['status' => 'success', 'message' => 'Donasi berhasil dibuat', 'data' => $food], 201);
    }

    // ==========================================
    // 5. UPDATE / EDIT DONASI
    // ==========================================
    public function updateDonation(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if (!$food || (string) $food->donor_id !== (string) $request->user()->_id) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            if (!in_array($food->status, ['available', 'pending', 'waiting_donor'])) return response()->json(['status' => 'error', 'message' => 'Donasi sudah diproses.'], 400);

            $request->validate(['name' => 'required|string|max:255', 'portion' => 'required']);

            $food->name = $request->name;
            $food->category = $request->category ?? $food->category;
            $food->portion = (string) $request->portion;
            $food->note = $request->note ?? $food->note;
            if ($request->has('collection_date')) $food->collection_date = $request->collection_date;
            if ($request->has('photo_url') && $request->photo_url != null) $food->photo_url = $request->photo_url;

            $food->save();
            return response()->json(['status' => 'success', 'message' => 'Donasi diperbarui!', 'data' => $food], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    // ==========================================
    // 6. BATALKAN DONASI (UBAH JADI INVALID)
    // ==========================================
    public function cancelDonation(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if (!$food || (string) $food->donor_id !== (string) $request->user()->_id) return response()->json(['status' => 'error', 'message' => 'Data donasi tidak ditemukan.'], 404);
            if (!in_array($food->status, ['available', 'pending', 'waiting_donor'])) return response()->json(['status' => 'error', 'message' => 'Donasi sudah diproses, tidak bisa dibatalkan.'], 400);

            $food->status = 'invalid';
            $food->save();

            return response()->json(['status' => 'success', 'message' => 'Donasi berhasil dibatalkan menjadi Invalid.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    // ==========================================
    // 7. HAPUS PERMANEN DONASI
    // ==========================================
    public function deleteDonation(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if ($food && (string) $food->donor_id === (string) $request->user()->_id) {
                if ($food->status !== 'invalid') return response()->json(['status' => 'error', 'message' => 'Hanya donasi Invalid yang dapat dihapus permanen.'], 400);
                $food->delete();
                return response()->json(['status' => 'success', 'message' => 'Donasi berhasil dihapus permanen.']);
            }
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus.'], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }
    
    // ==========================================
    // 8. RIWAYAT DONASI
    // ==========================================
    public function history(Request $request)
    {
        $history = Food::where('donor_id', (string) $request->user()->_id)->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $history], 200);
    }

    // ==========================================
    // 9. PROFIL DONOR
    // ==========================================
    public function getProfile(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => $request->user()], 200);
    }
    public function updateProfile(Request $request)
    {
        $request->user()->update($request->only(['name', 'restaurant_name', 'phone', 'address']));
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
