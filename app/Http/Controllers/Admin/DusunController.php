<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dusun; // <-- 1. Impor Model Dusun
use Illuminate\Http\Request; // <-- 2. Impor Request untuk menangani form
use Illuminate\Support\Facades\Redirect; // <-- 3. Impor Redirect untuk pindah halaman
use Illuminate\Validation\Rule;

class DusunController extends Controller
{
    /**
     * Menampilkan daftar semua dusun.
     */
    public function index()
    {
        // Ambil semua data dari tabel_dusun
        $dusunList = Dusun::all(); 

        // Kirim data ke view 'admin.dusun.index'
        return view('admin.dusun.index', [
            'dusunList' => $dusunList
        ]);
    }

    /**
     * Menampilkan form untuk menambah dusun baru.
     */
    public function create()
    {
        // Hanya tampilkan halaman form
        return view('admin.dusun.create');
    }

    /**
     * Menyimpan data dusun baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama_dusun' => 'required|string|max:100|unique:tabel_dusun'
        ], [
            'nama_dusun.required' => 'Nama dusun wajib diisi.',
            'nama_dusun.unique' => 'Nama dusun ini sudah ada.'
        ]);

        // 2. Simpan data ke database
        $dusun = new Dusun();
        $dusun->nama_dusun = $request->nama_dusun;
        $dusun->save();

        // 3. Arahkan kembali ke halaman index dengan pesan sukses
        return Redirect::route('dusun.index')->with('success', 'Data dusun berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // (Biarkan kosong untuk saat ini)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dusun $dusun)
    {
        return view('admin.dusun.edit', [
            'dusun' => $dusun
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dusun $dusun)
    {
        // Validasi, pastikan nama dusun unik KECUALI untuk dirinya sendiri
        $request->validate([
            'nama_dusun' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tabel_dusun')->ignore($dusun->id_dusun, 'id_dusun')
            ]
        ]);

        $dusun->nama_dusun = $request->nama_dusun;
        $dusun->save();

        return Redirect::route('dusun.index')->with('success', 'Data dusun berhasil diperbarui.');
    }

    public function destroy(Dusun $dusun)
    {
        try {
            $dusun->delete();
            return Redirect::route('dusun.index')->with('success', 'Data dusun berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani jika dusun tidak bisa dihapus (karena terhubung ke KK)
            return Redirect::route('dusun.index')->with('error', 'Gagal menghapus: Dusun ini mungkin masih terhubung ke data KK.');
        }
    }
}