<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\Delivery;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $query = Volunteer::with(['deliveries'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $allDeliveries = \App\Models\Delivery::whereNotNull('volunteer_id')->pluck('volunteer_id');
        $topVolId = $allDeliveries->countBy()->sortDesc()->keys()->first();
        $topVolunteer = $topVolId ? Volunteer::find($topVolId) : null;
        if ($topVolunteer) {
            $topVolunteer->deliveries_count = $allDeliveries->countBy()[$topVolId];
        }

        $stats = [
            'total'            => Volunteer::count(),
            'active'           => $allDeliveries->unique()->count(),
            'total_deliveries' => Delivery::count(),
            'top_volunteer'    => $topVolunteer,
        ];

        $volunteers = $query->paginate(10);
        foreach ($volunteers as $vol) {
            $vol->deliveries_count = $vol->deliveries->count();
        }

        return view('admin.volunteers.index', [
            'volunteers' => $volunteers,
            'stats'      => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string|max:500',
            'vehicle_type'  => 'nullable|string|max:50',
            'vehicle_plate' => 'nullable|string|max:20',
        ]);

        Volunteer::create($data);
        return redirect()->route('admin.volunteers.index')
            ->with('success', 'Volunteer berhasil ditambahkan!');
    }

    public function show(Volunteer $volunteer)
    {
        $volunteer->load([
            'deliveries' => fn($q) => $q->with(['food', 'donor', 'receiver'])->latest()
        ]);
        $volunteer->deliveries_count = $volunteer->deliveries->count();

        return view('admin.volunteers.show', compact('volunteer'));
    }

    public function destroy(Volunteer $volunteer)
    {
        $volunteer->delete();
        return redirect()->route('admin.volunteers.index')
            ->with('success', 'Volunteer berhasil dihapus!');
    }
}
