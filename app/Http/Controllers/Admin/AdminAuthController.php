<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Simpan last_login_at
            Auth::guard('admin')->user()->update(['last_login_at' => now()]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // FORGOT PASSWORD
    public function showForgotPassword()
    {
        return view('admin.forgot-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username'     => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin) {
            return back()->with('error', 'Username tidak ditemukan.');
        }

        $admin->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}