<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Impor Fasad Auth
use Illuminate\Support\Facades\Redirect; // <-- Impor Redirect
use App\Models\Warga;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login warga.
     */
    public function showLoginForm()
    {
        // Jika warga sudah login, langsung arahkan ke dashboard
        if (Auth::check() && Auth::user()->role == 'warga') {
            return Redirect::route('warga.dashboard');
        }
        return view('citizen.auth.login');
    }

    /**
     * Memproses data login yang dikirim dari form.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Coba lakukan login
        if (Auth::attempt($credentials)) {

            // 3. Login berhasil, cek rolenya
            $user = Auth::user();

            // Regenerate session
            $request->session()->regenerate();

            // --- INI LOGIKA PINTARNYA ---
            if ($user->role == 'warga') {
                // Jika rolenya 'warga', arahkan ke dashboard warga
                return Redirect::route('warga.dashboard')->with('success', 'Selamat datang!');

            } elseif ($user->role == 'admin' || $user->role == 'kades') {
                // Jika rolenya 'admin' atau 'kades', arahkan ke dashboard admin
                // Kita pakai 'dusun.index' sebagai halaman utama admin
                return redirect()->route('dusun.index'); 

            } else {
                // Jika rolenya tidak jelas, tolak
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Role akun Anda tidak valid.',
                ])->onlyInput('username');
            }
        }

        // 4. Login gagal (username atau password salah)
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Logout warga.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan ke halaman login warga
        return Redirect::route('warga.login.form');
    }

    /**
     * Menampilkan dashboard warga (setelah login).
     */
   public function dashboard()
    {
        // Ambil ID user yang sedang login
        $id_user_login = Auth::id();

        // Cari data warga yang terhubung dengan ID user tersebut
        // Kita pakai 'with' agar data KK dan Dusun ikut terambil
        $warga = Warga::with('kk.dusun')
                    ->where('id_user', $id_user_login)
                    ->first();

        // Jika karena suatu alasan data warga tidak terhubung (misal Admin lupa)
        if (!$warga) {
            // Logout paksa dan beri pesan error
            Auth::logout();
            return Redirect::route('warga.login.form')->withErrors([
                'username' => 'Akun Anda belum terhubung dengan data kependudukan. Harap hubungi Admin.'
            ]);
        }

        // Kirim data 'warga' ke view
        return view('citizen.dashboard', [
            'warga' => $warga
        ]);
    }
}