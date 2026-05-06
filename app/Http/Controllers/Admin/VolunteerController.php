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
        $query = Volunteer::withCount('deliveries')->with(['deliveries'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $topVolunteer = Volunteer::withCount('deliveries')->orderByDesc('deliveries_count')->first();

        $stats = [
            'total'            => Volunteer::count(),
            'active'           => Volunteer::has('deliveries')->count(),
            'total_deliveries' => Delivery::count(),
            'top_volunteer'    => $topVolunteer,
        ];

        return view('admin.volunteers.index', [
            'volunteers' => $query->paginate(10),
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
            'deliveries' => fn($q) => $q->with(['food','donor','receiver'])->latest()
        ]);
        $volunteer->loadCount('deliveries');

        return view('admin.volunteers.show', compact('volunteer'));
    }

    public function destroy(Volunteer $volunteer)
    {
        $volunteer->delete();
        return redirect()->route('admin.volunteers.index')
            ->with('success', 'Volunteer berhasil dihapus!');
    }
}
