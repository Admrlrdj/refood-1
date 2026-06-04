<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use Illuminate\Support\Facades\Validator;

class ReceiverController extends Controller
{
    // 1. GET DASHBOARD STATS & RECENT ACTIVITY
    public function dashboard(Request $request)
    {
        $receiverId = $request->user()->_id;

        // Statistik
        $diterima = Food::where('receiver_id', $receiverId)->where('status', 'completed')->count();
        $diperjalanan = Food::where('receiver_id', $receiverId)->whereIn('status', ['accepted', 'on_delivery'])->count();
        $requestAktif = Food::where('receiver_id', $receiverId)->where('status', 'waiting_donor')->count();

        // 1. Makanan Masuk Terkini (Donasi dari Donatur yang statusnya available)
        $incomingFoods = Food::where('status', 'available')
            ->whereNotNull('donor_id')
            ->whereNull('receiver_id')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // 2. Aktivitas Request Makanan (Request yang dibuat oleh Receiver ini)
        $recentRequests = Food::where('receiver_id', $receiverId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'received' => $diterima,
                    'on_delivery' => $diperjalanan,
                    'requests' => $requestAktif,
                ],
                'incoming_foods' => $incomingFoods,
                'recent_requests' => $recentRequests
            ]
        ], 200);
    }

    // 2. CARI MAKANAN (BROWSE DONASI TERSEDIA)
    public function getAvailableFoods(Request $request)
    {
        // Mengambil semua donasi yang dibuat oleh Donatur (donor_id tidak null) 
        // dan statusnya masih 'available' (belum di-request/diambil oleh penerima siapapun)
        $foods = Food::with('donor') // Jika kamu sudah set relasi belongsTo di Model Food
            ->where('status', 'available')
            ->whereNotNull('donor_id')
            ->whereNull('receiver_id')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $foods
        ], 200);
    }

    // 3. CREATE REQUEST MAKANAN (POST)
    public function createRequest(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'portion' => 'required',
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

        $foodRequest = \App\Models\Food::create([
            'name' => $request->name,
            'category' => $request->category,
            'portion' => (string) $request->portion,
            'donor_id' => null, // Karena ini Request, donor belum ada
            'receiver_id' => $request->user()->_id, // ID Penerima yang login
            'status' => 'waiting_donor',
            'collection_date' => \Carbon\Carbon::parse($request->collection_date),
            'note' => $request->note,
            'photo_url' => $photoUrl,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Request makanan berhasil diajukan!',
            'data' => $foodRequest
        ], 201);
    }

    // 4. GET PROFILE & UPDATE PROFILE (RECEIVER)
    public function getProfile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ], 200);
    }

    // 5. GET DETAIL FOOD (MASUK / REQUEST)
    public function getFoodDetail(Request $request, $id)
    {
        $food = \App\Models\Food::find($id);
        if (!$food) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        return response()->json(['status' => 'success', 'data' => $food], 200);
    }

    // 6. TERIMA / AMBIL DONASI DARI DONATUR
    public function acceptDonation(Request $request, $id)
    {
        $food = \App\Models\Food::where('_id', $id)->where('status', 'available')->first();
        if (!$food) return response()->json(['status' => 'error', 'message' => 'Donasi sudah diambil atau tidak tersedia'], 404);

        $food->receiver_id = $request->user()->_id;
        $food->status = 'accepted'; // Ubah status menjadi accepted (diterima)
        $food->save();

        return response()->json(['status' => 'success', 'message' => 'Donasi berhasil diterima! Menunggu relawan untuk mengantar.'], 200);
    }

    // 7. UPDATE REQUEST MAKANAN (EDIT)
    public function updateRequest(Request $request, $id)
    {
        $food = \App\Models\Food::where('_id', $id)->where('receiver_id', $request->user()->_id)->where('status', 'waiting_donor')->first();
        if (!$food) return response()->json(['status' => 'error', 'message' => 'Request tidak ditemukan atau sudah diproses'], 404);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'portion' => 'sometimes|required',
            'collection_date' => 'sometimes|required|date',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);

        if ($request->hasFile('photo')) {
            if ($food->photo_url) \Illuminate\Support\Facades\Storage::delete(str_replace('storage/', 'public/', $food->photo_url));
            $path = $request->file('photo')->store('public/foods');
            $food->photo_url = str_replace('public/', 'storage/', $path);
        }

        if ($request->has('name')) $food->name = $request->name;
        if ($request->has('category')) $food->category = $request->category;
        if ($request->has('portion')) $food->portion = (string) $request->portion;
        if ($request->has('collection_date')) $food->collection_date = \Carbon\Carbon::parse($request->collection_date);
        if ($request->has('note')) $food->note = $request->note;

        $food->save();
        return response()->json(['status' => 'success', 'message' => 'Request berhasil diperbarui'], 200);
    }

    // 8. DELETE REQUEST MAKANAN
    public function deleteRequest(Request $request, $id)
    {
        $food = \App\Models\Food::where('_id', $id)->where('receiver_id', $request->user()->_id)->where('status', 'waiting_donor')->first();
        if (!$food) return response()->json(['status' => 'error', 'message' => 'Request tidak ditemukan atau sudah diproses'], 404);

        if ($food->photo_url) \Illuminate\Support\Facades\Storage::delete(str_replace('storage/', 'public/', $food->photo_url));
        $food->delete();

        return response()->json(['status' => 'success', 'message' => 'Request berhasil dihapus'], 200);
    }

    // 9. GET RIWAYAT PENERIMA (HISTORY)
    public function history(Request $request)
    {
        // Ambil semua makanan yang receiver_id-nya adalah user ini
        // (Termasuk yang di-request sendiri, atau donasi donatur yang sudah di-accept)
        $foods = \App\Models\Food::where('receiver_id', $request->user()->_id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $foods], 200);
    }

    // 10. UPDATE PROFILE PENERIMA
    public function updateProfile(Request $request)
    {
        $receiver = $request->user();
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'pic_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
            'capacity_people' => 'sometimes|integer'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);

        if ($request->has('name')) $receiver->name = $request->name;
        if ($request->has('pic_name')) $receiver->pic_name = $request->pic_name;
        if ($request->has('phone')) $receiver->phone = $request->phone;
        if ($request->has('address')) $receiver->address = $request->address;
        if ($request->has('capacity_people')) $receiver->capacity_people = (int) $request->capacity_people;

        $receiver->save();
        return response()->json(['status' => 'success', 'message' => 'Profil berhasil diperbarui!', 'data' => $receiver], 200);
    }

    // 11. UPDATE SETTINGS PENERIMA
    public function updateSettings(Request $request)
    {
        $receiver = $request->user();
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'username' => 'sometimes|string|max:50',
            'old_password' => 'required_with:new_password|string',
            'new_password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);

        if ($request->has('username') && $request->username !== $receiver->username) {
            $newUsername = strtolower(str_replace(' ', '', $request->username));
            if (\App\Models\Receiver::where('username', $newUsername)->where('_id', '!=', $receiver->_id)->exists()) {
                return response()->json(['status' => 'error', 'message' => 'Username sudah digunakan.'], 422);
            }
            $receiver->username = $newUsername;
        }

        if ($request->has('new_password') && !empty($request->new_password)) {
            if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $receiver->password)) {
                return response()->json(['status' => 'error', 'message' => 'Password lama salah!'], 400);
            }
            $receiver->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $receiver->save();
        return response()->json(['status' => 'success', 'message' => 'Pengaturan berhasil diperbarui!'], 200);
    }
}
