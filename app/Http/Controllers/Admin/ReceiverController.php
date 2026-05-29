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
        $query = Receiver::with(['deliveries'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $allDeliveries = \App\Models\Delivery::whereNotNull('receiver_id')->pluck('receiver_id');
        $topReceiverId = $allDeliveries->countBy()->sortDesc()->keys()->first();
        $topReceiver = $topReceiverId ? Receiver::find($topReceiverId) : null;
        if ($topReceiver) {
            $topReceiver->deliveries_count = $allDeliveries->countBy()[$topReceiverId];
        }

        $stats = [
            'total'        => Receiver::count(),
            'active'       => $allDeliveries->unique()->count(),
            'total_foods'  => \App\Models\Delivery::where('status', 'delivered')->count(),
            'top_receiver' => $topReceiver,
        ];

        $receivers = $query->paginate(10);
        foreach ($receivers as $receiver) {
            $receiver->deliveries_count = $receiver->deliveries->count();
        }

        return view('admin.receivers.index', [
            'receivers' => $receivers,
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
            'deliveries' => fn($q) => $q->with(['food', 'donor'])->latest()
        ]);
        $receiver->deliveries_count = $receiver->deliveries->count();

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

    public function json(Receiver $receiver)
    {
        return response()->json($receiver);
    }

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
