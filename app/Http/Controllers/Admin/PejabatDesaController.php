<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PejabatDesa;
use App\Models\User; // <-- PENTING
use App\Models\Dusun; // <-- PENTING
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash; // <-- PENTING

class PejabatDesaController extends Controller
{
    public function index()
    {
        $pejabatList = PejabatDesa::all();
        return view('admin.pejabat_desa.index', compact('pejabatList'));
    }

    public function create()
    {
        // Kita butuh data dusun untuk dropdown (jika yang dibuat adalah akun Kadus)
        $dusunList = Dusun::all();
        return view('admin.pejabat_desa.create', compact('dusunList'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Data Pejabat
        $request->validate([
            'nama_pejabat' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'nip' => 'nullable|string|max:30',
            'tanggal_lahir' => 'nullable|date',

            // Validasi Tambahan untuk Akun (Hanya jika dicentang)
            'username' => 'required_if:buat_akun,on|nullable|string|unique:tabel_users,username',
            'role_akun' => 'required_if:buat_akun,on|nullable|in:kades,kadus,admin',
            'id_dusun_akun' => 'nullable|required_if:role_akun,kadus|exists:tabel_dusun,id_dusun',
        ]);

        // 2. Simpan Data Pejabat (Untuk Tanda Tangan)
        $pejabat = new PejabatDesa();
        $pejabat->nama_pejabat = $request->nama_pejabat;
        $pejabat->jabatan = $request->jabatan;
        $pejabat->nip = $request->nip;
        $pejabat->tanggal_lahir = $request->tanggal_lahir;
        $pejabat->save();

        // 3. Logika Pembuatan Akun Login (Jika dicentang)
        if ($request->has('buat_akun')) {
            $user = new User();
            $user->nama_lengkap = $request->nama_pejabat; // Ambil dari nama pejabat
            $user->username = $request->username;
            $user->password = Hash::make('123456'); // Default Password
            $user->role = $request->role_akun;

            // Jika role-nya kadus, simpan id_dusun-nya
            if ($request->role_akun == 'kadus') {
                $user->id_dusun = $request->id_dusun_akun;
            }

            $user->save();
        }

        return Redirect::route('pejabat-desa.index')->with('success', 'Data pejabat berhasil ditambahkan' . ($request->has('buat_akun') ? ' dan Akun Login telah dibuat.' : '.'));
    }

    public function edit(PejabatDesa $pejabatDesa)
    {
        return view('admin.pejabat_desa.edit', ['pejabat' => $pejabatDesa]);
    }

    public function update(Request $request, PejabatDesa $pejabatDesa)
    {
        // Update Pejabat (Logika akun biasanya tidak diedit dari sini, tapi dari Manajemen Akun)
        $request->validate([
            'nama_pejabat' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'nip' => 'nullable|string|max:30',
            'tanggal_lahir' => 'nullable|date',
        ]);

        $pejabatDesa->nama_pejabat = $request->nama_pejabat;
        $pejabatDesa->jabatan = $request->jabatan;
        $pejabatDesa->nip = $request->nip;
        $pejabatDesa->tanggal_lahir = $request->tanggal_lahir;
        $pejabatDesa->save();

        return Redirect::route('pejabat-desa.index')->with('success', 'Data pejabat desa berhasil diperbarui.');
    }

    public function destroy(PejabatDesa $pejabatDesa)
    {
        try {
            $pejabatDesa->delete();
            return Redirect::route('pejabat-desa.index')->with('success', 'Data pejabat desa berhasil dihapus.');
        } catch (\Exception $e) {
            return Redirect::route('pejabat-desa.index')->with('error', 'Gagal menghapus data.');
        }
    }
}