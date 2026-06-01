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

        $volunteers = $query->paginate(10);
        $allVols = Volunteer::with('deliveries')->get();
        $topVolunteer = $allVols->sortByDesc(fn($v) => $v->deliveries ? $v->deliveries->count() : 0)->first();

        $stats = [
            'total'            => Volunteer::count(),
            'active'           => $allVols->filter(fn($v) => $v->deliveries && $v->deliveries->count() > 0)->count(),
            'total_deliveries' => Delivery::whereNotNull('volunteer_id')->count(),
            'top_volunteer'    => $topVolunteer,
        ];

        return view('admin.volunteers.index', compact('volunteers', 'stats'));
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
        return redirect()->route('admin.volunteers.index')->with('success', 'Volunteer berhasil ditambahkan!');
    }

    public function show($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->load(['deliveries' => fn($q) => $q->with(['food', 'donor', 'receiver'])->latest()]);

        return view('admin.volunteers.show', compact('volunteer'));
    }

    public function destroy($id)
    {
        Volunteer::findOrFail($id)->delete();
        return redirect()->route('admin.volunteers.index')->with('success', 'Volunteer berhasil dihapus!');
    }
}
