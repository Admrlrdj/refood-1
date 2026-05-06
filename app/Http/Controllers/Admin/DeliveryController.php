<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Food;
use App\Models\Donor;
use App\Models\Receiver;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
{
    $query = Delivery::with(['food', 'donor', 'receiver', 'volunteer'])->latest();
 
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->whereHas('food', fn($f) => $f->where('name', 'like', '%'.$request->search.'%'))
              ->orWhere('status', 'like', '%'.$request->search.'%')
              ->orWhereHas('receiver', fn($r) => $r->where('name', 'like', '%'.$request->search.'%'));
        });
    }
 
    match($request->sort) {
        'status' => $query->orderBy('status'),
        'name'   => $query->orderByRaw('(SELECT name FROM foods WHERE foods.id = deliveries.food_id)'),
        default  => $query->latest(),
    };
 
    $stats = [
        'completed'   => Delivery::where('status', 'completed')->count(),
        'delivered'   => Delivery::where('status', 'delivered')->count(),
        'on_delivery' => Delivery::where('status', 'on_delivery')->count(),
    ];
 
    return view('admin.deliveries.index', [
        'deliveries'      => $query->paginate(8),
        'stats'           => $stats,
        'totalDeliveries' => Delivery::count(),
    ]);
}

    public function create()
    {
        return view('admin.deliveries.form', [
            'foods'      => Food::where('status', 'available')->get(),
            'donors'     => Donor::all(),
            'receivers'  => Receiver::all(),
            'volunteers' => Volunteer::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'food_id'      => 'required|exists:foods,id',
            'donor_id'     => 'nullable|exists:donors,id',
            'receiver_id'  => 'nullable|exists:receivers,id',
            'volunteer_id' => 'nullable|exists:volunteers,id',
            'status'       => 'required|in:pending,on_delivery,delivered,failed',
            'pickup_time'  => 'nullable|date',
            'eta_minutes'  => 'nullable|integer|min:1',
            'note'         => 'nullable|string|max:1000',
            'lat'          => 'nullable|numeric',
            'lng'          => 'nullable|numeric',
        ]);

        Delivery::create($data);

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Delivery berhasil ditambahkan!');
    }

    public function show(Delivery $delivery)
    {
        $delivery->load(['food', 'donor', 'receiver', 'volunteer']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    public function edit(Delivery $delivery)
    {
        return view('admin.deliveries.form', [
            'delivery'   => $delivery->load(['food','donor','receiver','volunteer']),
            'foods'      => Food::all(),
            'donors'     => Donor::all(),
            'receivers'  => Receiver::all(),
            'volunteers' => Volunteer::all(),
        ]);
    }

    public function update(Request $request, Delivery $delivery)
    {
        $data = $request->validate([
            'food_id'      => 'required|exists:foods,id',
            'donor_id'     => 'nullable|exists:donors,id',
            'receiver_id'  => 'nullable|exists:receivers,id',
            'volunteer_id' => 'nullable|exists:volunteers,id',
            'status'       => 'required|in:pending,on_delivery,delivered,failed',
            'pickup_time'  => 'nullable|date',
            'eta_minutes'  => 'nullable|integer|min:1',
            'note'         => 'nullable|string|max:1000',
            'lat'          => 'nullable|numeric',
            'lng'          => 'nullable|numeric',
        ]);

        $delivery->update($data);

        return redirect()->route('admin.deliveries.show', $delivery)
            ->with('success', 'Delivery berhasil diupdate!');
    }

    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Delivery berhasil dihapus!');
    }

    public function updateStatus(Request $request, Delivery $delivery)
    {
        $request->validate(['status' => 'required|in:pending,on_delivery,delivered,failed']);
        $delivery->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $delivery->status]);
    }
}
