<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi username & password.
     * Sesuai kebutuhan: menampilkan pesan "Tidak Valid" jika kredensial salah,
     * dan mengarahkan ke dashboard jika berhasil.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['username' => 'Tidak Valid. Username atau password salah.'])
                ->onlyInput('username');
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();

            return back()->withErrors(['username' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        $request->session()->regenerate();

        ActivityLog::catat('Login', "{$user->name} ({$user->role}) berhasil login.");

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout dan hapus sesi.
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::catat('Logout', "{$user->name} ({$user->role}) logout.");
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
