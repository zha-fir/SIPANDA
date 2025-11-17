<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisSurat; // <-- Untuk mengambil daftar surat
use App\Models\AjuanSurat; // <-- Untuk menyimpan ajuan
use Illuminate\Support\Facades\Auth; // <-- Untuk tahu siapa yang login
use Illuminate\Support\Facades\Redirect;

class AjuanSuratController extends Controller
{
    /**
     * Menampilkan halaman form untuk membuat ajuan baru.
     */
    public function create()
    {
        // Ambil semua jenis surat yang sudah dibuat Admin
        $jenisSuratList = JenisSurat::all();

        // Ambil data warga yang sedang login
        $warga = Auth::user()->warga; // Ingat relasi user->warga yang kita buat

        // Tampilkan view dan kirim data ke sana
        return view('citizen.ajuan.create', [
            'jenisSuratList' => $jenisSuratList,
            'warga' => $warga
        ]);
    }

    /**
     * Menyimpan ajuan surat baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'id_jenis_surat' => 'required|integer|exists:tabel_jenis_surat,id_jenis_surat',
            'keperluan' => 'required|string|max:255'
        ], [
            'id_jenis_surat.required' => 'Anda harus memilih salah satu jenis surat.',
            'keperluan.required' => 'Keperluan wajib diisi.'
        ]);

        // 2. Ambil data warga yang sedang login
        $warga = Auth::user()->warga;

        // 3. Simpan data ajuan baru
        $ajuan = new AjuanSurat();
        $ajuan->id_warga = $warga->id_warga;
        $ajuan->id_jenis_surat = $request->id_jenis_surat;
        $ajuan->status = 'BARU'; // Status awal selalu 'BARU'
        $ajuan->save();

        // 4. Arahkan kembali ke dashboard dengan pesan sukses
        return Redirect::route('warga.dashboard')->with('success', 'Ajuan surat Anda telah berhasil terkirim. Silakan tunggu konfirmasi dari Admin.');
    }
}