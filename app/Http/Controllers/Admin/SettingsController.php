<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // ── Change Password ──────────────────────────
        if ($request->filled('change_password')) {
            $request->validate([
                'current_password' => 'required',
                'new_password'     => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->with('error', 'Password saat ini tidak sesuai!');
            }

            $admin->update(['password' => Hash::make($request->new_password)]);
            return back()->with('success', 'Password berhasil diubah!');
        }

        // ── Save profile + system settings ───────────
        $request->validate(['name' => 'required|string|max:255']);

        $admin->update(['name' => $request->name]);

        // Simpan preferensi ke session (persist per-login)
        session([
            'appearance' => $request->input('appearance', 'light'),
            'font_size'  => $request->input('font_size', 'medium'),
            'language'   => $request->input('language', 'en'),
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}