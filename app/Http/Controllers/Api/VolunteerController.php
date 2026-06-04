<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;

class VolunteerController extends Controller
{
    // 1. GET DASHBOARD STATS & AVAILABLE JOBS
    public function dashboard(Request $request)
    {
        $volunteerId = $request->user()->_id;

        // Tugas Aktif (Makanan yang sedang diantar oleh relawan ini)
        $activeJobs = Food::where('volunteer_id', $volunteerId)->where('status', 'on_delivery')->count();

        // Tugas Selesai (Makanan yang sudah sukses diantar)
        $completedJobs = Food::where('volunteer_id', $volunteerId)->where('status', 'completed')->count();

        // Panggilan Pengantaran (Semua donasi yang berstatus 'accepted' oleh penerima, tapi belum ada relawan yang mengambil)
        $availableJobs = Food::with(['donor', 'receiver'])
            ->where('status', 'accepted')
            ->whereNull('volunteer_id')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'active' => $activeJobs,
                    'completed' => $completedJobs,
                ],
                'available_jobs' => $availableJobs
            ]
        ], 200);
    }

    // 2. GET PROFILE
    public function getProfile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ], 200);
    }
}
