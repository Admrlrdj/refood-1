<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Donor;
use App\Models\Receiver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::with(['donor', 'receiver'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('admin.foods.index', [
            'foods'     => $query->paginate(15),
            'donors'    => Donor::all(),
            'receivers' => Receiver::all(),
            'totalFoods'   => Food::count(),
            'invalidFoods' => Food::where('status', 'invalid')->count(),
        ]);
    }

    public function create()
    {
        return view('admin.foods.form', [
            'donors'    => Donor::all(),
            'receivers' => Receiver::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'nullable|string|max:100',
            'portion'         => 'nullable|integer|min:1',
            'donor_id'        => 'nullable|exists:donors,id',
            'receiver_id'     => 'nullable|exists:receivers,id',
            'status'          => 'required|in:available,taken,delivered,invalid',
            'collection_date' => 'nullable|date',
            'note'            => 'nullable|string|max:1000',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('foods', 'public');
        }

        Food::create($data);

        return redirect()->route('admin.foods.index')
            ->with('success', 'Makanan berhasil ditambahkan!');
    }

    public function show(Food $food)
    {
        $food->load(['donor', 'receiver']);
        return view('admin.foods.show', compact('food'));
    }

    public function edit(Food $food)
    {
        return view('admin.foods.form', [
            'food'      => $food->load(['donor', 'receiver']),
            'donors'    => Donor::all(),
            'receivers' => Receiver::all(),
        ]);
    }

    public function update(Request $request, Food $food)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'category'        => 'nullable|string|max:100',
            'portion'         => 'nullable|integer|min:1',
            'donor_id'        => 'nullable|exists:donors,id',
            'receiver_id'     => 'nullable|exists:receivers,id',
            'status'          => 'required|in:available,taken,delivered,invalid',
            'collection_date' => 'nullable|date',
            'note'            => 'nullable|string|max:1000',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($food->photo) Storage::disk('public')->delete($food->photo);
            $data['photo'] = $request->file('photo')->store('foods', 'public');
        }

        $food->update($data);

        return redirect()->route('admin.foods.show', $food)
            ->with('success', 'Makanan berhasil diupdate!');
    }

    public function destroy(Food $food)
    {
        if ($food->photo) Storage::disk('public')->delete($food->photo);
        $food->delete();

        return redirect()->route('admin.foods.index')
            ->with('success', 'Makanan berhasil dihapus!');
    }

    /**
     * Mark food as invalid — notifikasi ke donor (bisa dikembangkan ke email)
     */
    public function markInvalid(Food $food)
    {
        $food->update(['status' => 'invalid']);

        // TODO: Kirim notifikasi ke donor $food->donor (email, dll)

        return redirect()->route('admin.foods.show', $food)
            ->with('success', 'Makanan ditandai sebagai invalid.');
    }
}
