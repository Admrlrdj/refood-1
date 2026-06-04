<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use MongoDB\BSON\ObjectId;

class ReceiverController extends Controller
{
    public function dashboard(Request $request)
    {
        $receiverId = (string) $request->user()->_id;

        $incomingFoods = Food::with('donor')
            ->where('status', 'available')
            ->where(function ($query) {
                $query->whereNull('receiver_id')
                    ->orWhere('receiver_id', '');
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Tampilkan juga makanan yang sudah diklaim (accepted & on_delivery)
        $activeRequests = Food::with('donor')
            ->where('receiver_id', $receiverId)
            ->whereIn('status', ['waiting_donor', 'requested', 'accepted', 'on_delivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        $receivedCount = Food::where('receiver_id', $receiverId)
            ->where('status', 'completed')
            ->count();

        $onDeliveryCount = Food::where('receiver_id', $receiverId)
            ->whereIn('status', ['accepted', 'on_delivery'])
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'incoming_foods' => $incomingFoods,
                'active_requests' => $activeRequests,
                'summary' => [
                    'received' => $receivedCount,
                    'on_delivery' => $onDeliveryCount,
                    'requests' => $activeRequests->count(),
                ]
            ]
        ], 200);
    }

    public function getAvailableFoods(Request $request)
    {
        $foods = Food::with('donor')
            ->where('status', 'available')
            ->where(function ($query) {
                $query->whereNull('receiver_id')
                    ->orWhere('receiver_id', '');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $foods], 200);
    }

    public function createRequest(Request $request)
    {
        $request->validate(['name' => 'required|string', 'portion' => 'required']);

        $food = Food::create([
            'receiver_id' => (string) $request->user()->_id,
            'name' => $request->name,
            'category' => $request->category ?? 'Umum',
            'portion' => (string) $request->portion,
            'note' => $request->note ?? '',
            'status' => 'waiting_donor',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Request berhasil dibuat!', 'data' => $food], 201);
    }

    public function getFoodDetail(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::with('donor')->where('_id', $objectId)->first();
            if (!$food) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            return response()->json(['status' => 'success', 'data' => $food], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    // ==========================================
    // FUNGSI AMBIL DONASI OLEH YAYASAN
    // ==========================================
    public function acceptDonation(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if (!$food || $food->status !== 'available') {
                return response()->json(['status' => 'error', 'message' => 'Donasi sudah diambil yayasan lain atau tidak tersedia.'], 400);
            }

            $food->receiver_id = (string) $request->user()->_id;
            $food->status = 'accepted';
            $food->save();

            return response()->json(['status' => 'success', 'message' => 'Donasi berhasil diklaim! Relawan akan segera menjemputnya.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    public function updateRequest(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if (!$food || (string) $food->receiver_id !== (string) $request->user()->_id) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan atau akses ditolak.'], 404);
            }

            if (!in_array($food->status, ['waiting_donor', 'requested'])) {
                return response()->json(['status' => 'error', 'message' => 'Request sudah diproses donatur.'], 400);
            }

            $food->name = $request->name;
            $food->portion = (string) $request->portion;
            $food->note = $request->note ?? $food->note;
            $food->save();

            return response()->json(['status' => 'success', 'message' => 'Request diperbarui!', 'data' => $food], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    public function deleteRequest(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if ($food && (string) $food->receiver_id === (string) $request->user()->_id) {
                $food->delete();
                return response()->json(['status' => 'success', 'message' => 'Request dibatalkan.']);
            }
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus.'], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    public function history(Request $request)
    {
        $history = Food::where('receiver_id', (string) $request->user()->_id)->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $history], 200);
    }
    public function getProfile(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => $request->user()], 200);
    }
    public function updateProfile(Request $request)
    {
        $request->user()->update($request->only(['name', 'pic_name', 'phone', 'address']));
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
