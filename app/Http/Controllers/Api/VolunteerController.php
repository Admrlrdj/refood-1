<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use MongoDB\BSON\ObjectId;

class VolunteerController extends Controller
{
    // ==========================================
    // 1. GET DASHBOARD RELAWAN
    // ==========================================
    public function dashboard(Request $request)
    {
        $volunteerId = (string) $request->user()->_id;

        // Panggilan Pengantaran: Status 'accepted' (sudah diklaim yayasan tapi belum ada relawan)
        $availableJobs = Food::with(['donor', 'receiver'])
            ->where('status', 'accepted')
            ->where(function ($q) {
                $q->whereNull('volunteer_id')->orWhere('volunteer_id', '');
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        // Tugas Aktif: Status 'on_delivery' milik relawan ini
        $activeJobs = Food::with(['donor', 'receiver'])
            ->where('volunteer_id', $volunteerId)
            ->where('status', 'on_delivery')
            ->orderBy('updated_at', 'desc')
            ->get();

        $completedCount = Food::where('volunteer_id', $volunteerId)
            ->where('status', 'completed')
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => ['completed' => $completedCount, 'active' => $activeJobs->count()],
                'available_jobs' => $availableJobs,
                'active_jobs' => $activeJobs
            ]
        ], 200);
    }

    // ==========================================
    // 2. GET DETAIL TUGAS PENGANTARAN
    // ==========================================
    public function getJobDetail(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::with(['donor', 'receiver'])->where('_id', $objectId)->first();

            if (!$food) return response()->json(['status' => 'error', 'message' => 'Tugas tidak ditemukan.'], 404);
            return response()->json(['status' => 'success', 'data' => $food], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    // ==========================================
    // 3. AMBIL TUGAS (ACCEPT JOB)
    // ==========================================
    public function acceptJob(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if (!$food || $food->status !== 'accepted') {
                return response()->json(['status' => 'error', 'message' => 'Tugas sudah diambil relawan lain.'], 400);
            }

            $food->volunteer_id = (string) $request->user()->_id;
            $food->status = 'on_delivery';
            $food->save();

            return response()->json(['status' => 'success', 'message' => 'Berhasil mengambil tugas pengantaran!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    // ==========================================
    // 4. SELESAIKAN TUGAS PENGANTARAN
    // ==========================================
    public function updateJobStatus(Request $request, $id)
    {
        try {
            $objectId = new ObjectId($id);
            $food = Food::where('_id', $objectId)->first();

            if (!$food || (string) $food->volunteer_id !== (string) $request->user()->_id) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }

            $request->validate(['status' => 'required|string']);
            $food->status = $request->status; // Menerima status 'completed'
            $food->save();

            return response()->json(['status' => 'success', 'message' => 'Status pengantaran diperbarui!', 'data' => $food], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format ID salah.'], 500);
        }
    }

    // ==========================================
    // 5. HISTORY & PROFILE 
    // ==========================================
    public function history(Request $request)
    {
        $history = Food::with(['donor', 'receiver'])->where('volunteer_id', (string) $request->user()->_id)->orderBy('updated_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $history], 200);
    }
    public function getProfile(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => $request->user()], 200);
    }
    public function updateProfile(Request $request)
    {
        $request->user()->update($request->only(['name', 'vehicle', 'phone', 'address']));
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
    