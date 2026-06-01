<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Food;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = Donor::with(['foods'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $donors = $query->paginate(10);
        $allDonors = Donor::with('foods')->get();
        $topDonor = $allDonors->sortByDesc(fn($d) => $d->foods ? $d->foods->count() : 0)->first();

        $stats = [
            'total'           => Donor::count(),
            'active'          => $allDonors->filter(fn($d) => $d->foods && $d->foods->count() > 0)->count(),
            'total_donations' => Food::count(),
            'top_donor'       => $topDonor,
        ];

        return view('admin.donors.index', compact('donors', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:individual,corporate',
            'pic_name' => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'address'  => 'nullable|string|max:500',
        ]);

        Donor::create($data);
        return redirect()->route('admin.donors.index')->with('success', 'Donor berhasil ditambahkan!');
    }

    public function show($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->load(['foods' => fn($q) => $q->with(['receiver', 'deliveries'])->latest()]);

        return view('admin.donors.show', compact('donor'));
    }

    public function update(Request $request, $id)
    {
        $donor = Donor::findOrFail($id);
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:individual,corporate',
            'pic_name' => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'address'  => 'nullable|string|max:500',
        ]);

        $donor->update($data);
        return redirect()->route('admin.donors.index')->with('success', 'Donor berhasil diupdate!');
    }

    public function destroy($id)
    {
        Donor::findOrFail($id)->delete();
        return redirect()->route('admin.donors.index')->with('success', 'Donor berhasil dihapus!');
    }

    public function history($id)
    {
        $foods = Food::where('donor_id', $id)->with('deliveries')->latest()->take(20)->get();
        $result = $foods->map(function ($food) {
            $delivery = $food->deliveries->first();
            return [
                'food_name' => $food->name,
                'status'    => $delivery->status ?? 'pending',
                'date'      => $food->created_at->format('d M Y'),
            ];
        });
        return response()->json($result);
    }
}
