<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Halaman login
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Proses login + redirect per role
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Email atau password salah!');
        }

        // Regenerate session (security)
        $request->session()->regenerate();

        // Ambil role user
        $role = Auth::user()->role;

        /*
        |--------------------------------------------------------------------------
        | REDIRECT SESUAI ROLE (PERMINTAAN KLIEN)
        |--------------------------------------------------------------------------
        | Admin        → Home
        | Kepsek       → Home
        | Wali Kelas   → Home
        | Guru BK      → Home
        | Siswa        → Home
        */
        switch ($role) {
            case 'admin':
            case 'kepsek':
            case 'guru_bk':
            case 'guru':
            case 'siswa':
                return redirect()->route('dashboard');

            default:
                Auth::logout();
                return redirect('/login')->with('error', 'Role tidak dikenali');
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
