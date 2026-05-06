<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receiver;
use App\Models\Delivery;
use Illuminate\Http\Request;

class ReceiverController extends Controller
{
        public function index(Request $request)
    {
        $query = Receiver::withCount('deliveries')->with(['deliveries'])->latest();
    
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
    
        $topReceiver = Receiver::withCount('deliveries')->orderByDesc('deliveries_count')->first();
    
        $stats = [
            'total'        => Receiver::count(),
            'active'       => Receiver::has('deliveries')->count(),
            'total_foods'  => \App\Models\Delivery::where('status','delivered')->count(),
            'top_receiver' => $topReceiver,
        ];
    
        return view('admin.receivers.index', [
            'receivers' => $query->paginate(10),
            'stats'     => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|in:orphanage,foundation,community,school,other',
            'pic_name'        => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:500',
            'capacity_people' => 'nullable|integer|min:1',
            'need_level'      => 'nullable|integer|min:0|max:100',
        ]);

        Receiver::create($data);
        return redirect()->route('admin.receivers.index')->with('success', 'Receiver berhasil ditambahkan!');
    }

    public function show(Receiver $receiver)
    {
        $receiver->load([
            'deliveries' => fn($q) => $q->with(['food','donor'])->latest()
        ]);
        $receiver->loadCount('deliveries');
    
        return view('admin.receivers.show', compact('receiver'));
    }

    public function update(Request $request, Receiver $receiver)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|in:orphanage,foundation,community,school,other',
            'pic_name'        => 'nullable|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:500',
            'capacity_people' => 'nullable|integer|min:1',
            'need_level'      => 'nullable|integer|min:0|max:100',
        ]);

        $receiver->update($data);
        return redirect()->route('admin.receivers.index')->with('success', 'Receiver berhasil diupdate!');
    }

    public function destroy(Receiver $receiver)
    {
        $receiver->delete();
        return redirect()->route('admin.receivers.index')->with('success', 'Receiver berhasil dihapus!');
    }

    /** API: JSON data untuk edit modal */
    public function json(Receiver $receiver)
    {
        return response()->json($receiver);
    }

    /** API: Detail + history untuk detail modal */
    public function detail(Receiver $receiver)
    {
        $history = Delivery::where('receiver_id', $receiver->id)
            ->with('food')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($d) => [
                'food_name' => $d->food->name ?? '—',
                'status'    => $d->status,
                'date'      => $d->created_at->format('d M Y'),
            ]);

        return response()->json(array_merge($receiver->toArray(), [
            'history' => $history,
        ]));
    }
}
