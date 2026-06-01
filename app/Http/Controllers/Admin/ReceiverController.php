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

        $receivers = $query->paginate(10);
        $allReceivers = Receiver::with('deliveries')->get();
        $topReceiver = $allReceivers->sortByDesc(fn($r) => $r->deliveries ? $r->deliveries->count() : 0)->first();

        $stats = [
            'total'        => Receiver::count(),
            'active'       => $allReceivers->filter(fn($r) => $r->deliveries && $r->deliveries->count() > 0)->count(),
            'total_foods'  => Delivery::where('status', 'delivered')->count(),
            'top_receiver' => $topReceiver,
        ];

        return view('admin.receivers.index', compact('receivers', 'stats'));
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

    public function show($id)
    {
        $receiver = Receiver::findOrFail($id);
        $receiver->load(['deliveries' => fn($q) => $q->with(['food', 'donor'])->latest()]);

        return view('admin.receivers.show', compact('receiver'));
    }

    public function update(Request $request, $id)
    {
        $receiver = Receiver::findOrFail($id);
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

    public function destroy($id)
    {
        Receiver::findOrFail($id)->delete();
        return redirect()->route('admin.receivers.index')->with('success', 'Receiver berhasil dihapus!');
    }

    public function json($id)
    {
        return response()->json(Receiver::findOrFail($id));
    }

    public function detail($id)
    {
        $receiver = Receiver::findOrFail($id);
        $history = Delivery::where('receiver_id', $id)
            ->with('food')->latest()->take(10)->get()
            ->map(fn($d) => [
                'food_name' => $d->food->name ?? '—',
                'status'    => $d->status,
                'date'      => $d->created_at->format('d M Y'),
            ]);

        return response()->json(array_merge($receiver->toArray(), ['history' => $history]));
    }

    public function verify($id)
    {
        $receiver = Receiver::findOrFail($id);
        $receiver->is_verified = true;
        $receiver->save();

        return back()->with('success', 'Akun Yayasan berhasil diverifikasi!');
    }

    public function reject($id)
    {
        $receiver = Receiver::findOrFail($id);
        $receiver->is_verified = false;
        $receiver->save();

        return back()->with('success', 'Status Yayasan diubah menjadi Belum Terverifikasi / Ditolak.');
    }
}
