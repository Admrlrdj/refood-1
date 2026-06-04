<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Food;

class VolunteerController extends Controller
{
    // ==========================================
    // 1. GET DASHBOARD (Radar Tugas)
    // ==========================================
    public function dashboard(Request $request)
    {
        $volunteerId = $request->user()->_id;

        $activeJobs = Food::where('volunteer_id', $volunteerId)->where('status', 'on_delivery')->count();
        $completedJobs = Food::where('volunteer_id', $volunteerId)->where('status', 'completed')->count();

        // Cari makanan yang sudah disetujui penerima tapi belum ada relawan yang ambil
        $availableJobs = Food::where('status', 'accepted')
            ->whereNull('volunteer_id')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => ['active' => $activeJobs, 'completed' => $completedJobs],
                'available_jobs' => $availableJobs
            ]
        ], 200);
    }

    // ==========================================
    // 2. GET HISTORY (Aktif & Selesai)
    // ==========================================
    public function history(Request $request)
    {
        $volunteerId = $request->user()->_id;

        $activeJobs = Food::where('volunteer_id', $volunteerId)->where('status', 'on_delivery')->orderBy('updated_at', 'desc')->get();
        $completedJobs = Food::where('volunteer_id', $volunteerId)->where('status', 'completed')->orderBy('updated_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'active' => $activeJobs,
                'completed' => $completedJobs
            ]
        ], 200);
    }

    // ==========================================
    // 3. AMBIL TUGAS (Accept Job)
    // ==========================================
    public function acceptJob(Request $request, $id)
    {
        $food = Food::find($id);

        if (!$food || $food->status !== 'accepted' || $food->volunteer_id !== null) {
            return response()->json(['status' => 'error', 'message' => 'Tugas ini tidak tersedia atau sudah diambil orang lain.'], 400);
        }

        $food->volunteer_id = $request->user()->_id;
        $food->status = 'on_delivery'; // Ubah status menjadi sedang diantar
        $food->save();

        return response()->json(['status' => 'success', 'message' => 'Tugas berhasil diambil! Silakan jemput donasi.'], 200);
    }

    // ==========================================
    // 4. SELESAIKAN TUGAS (Update Status)
    // ==========================================
    public function updateJobStatus(Request $request, $id)
    {
        $food = Food::find($id);

        if (!$food || (string) $food->volunteer_id !== (string) $request->user()->_id) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak valid.'], 403);
        }

        $request->validate(['status' => 'required|in:completed,cancelled']);

        $food->status = $request->status;
        $food->save();

        return response()->json(['status' => 'success', 'message' => 'Status pengantaran berhasil diperbarui!'], 200);
    }

    // ==========================================
    // 5. PROFILE & SETTINGS
    // ==========================================
    public function getProfile(Request $request)
    {
        return response()->json(['status' => 'success', 'data' => $request->user()], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $user->update($request->only(['name', 'phone', 'address', 'vehicle_type', 'vehicle_plate']));
        return response()->json(['status' => 'success', 'message' => 'Profil diperbarui']);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();
        if ($request->has('username')) $user->username = strtolower(str_replace(' ', '', $request->username));
        if ($request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) return response()->json(['status' => 'error', 'message' => 'Password lama salah'], 400);
            $user->password = Hash::make($request->new_password);
        }
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'Pengaturan diperbarui']);
    }
}
