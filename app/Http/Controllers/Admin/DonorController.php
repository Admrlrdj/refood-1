<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Food;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = Donor::with(['foods'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $allFoods = \App\Models\Food::whereNotNull('donor_id')->pluck('donor_id');
        $topDonorId = $allFoods->countBy()->sortDesc()->keys()->first();
        $topDonor = $topDonorId ? Donor::find($topDonorId) : null;
        if ($topDonor) {
            $topDonor->foods_count = $allFoods->countBy()[$topDonorId];
        }

        $stats = [
            'total'           => Donor::count(),
            'active'          => $allFoods->unique()->count(),
            'total_donations' => \App\Models\Food::count(),
            'top_donor'       => $topDonor,
        ];

        $donors = $query->paginate(10);
        foreach ($donors as $donor) {
            $donor->foods_count = $donor->foods->count();
        }

        return view('admin.donors.index', [
            'donors' => $donors,
            'stats'  => $stats,
        ]);
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

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donor berhasil ditambahkan!');
    }

    public function show(Donor $donor)
    {
        $donor->load([
            'foods' => fn($q) => $q->with(['receiver', 'deliveries'])->latest()
        ]);
        $donor->foods_count = $donor->foods->count();

        return view('admin.donors.show', compact('donor'));
    }

    public function update(Request $request, Donor $donor)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:individual,corporate',
            'pic_name' => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'address'  => 'nullable|string|max:500',
        ]);

        $donor->update($data);

        return redirect()->route('admin.donors.index')
            ->with('success', 'Donor berhasil diupdate!');
    }

    public function destroy(Donor $donor)
    {
        $donor->delete();
        return redirect()->route('admin.donors.index')
            ->with('success', 'Donor berhasil dihapus!');
    }

    public function history(Donor $donor)
    {
        $foods = Food::where('donor_id', $donor->id)
            ->with('deliveries')
            ->latest()
            ->take(20)
            ->get();

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
