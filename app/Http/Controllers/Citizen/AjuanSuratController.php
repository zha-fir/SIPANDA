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
        // 1. Validasi dasar
        $request->validate([
            'id_jenis_surat' => 'required|integer|exists:tabel_jenis_surat,id_jenis_surat',
            'keperluan' => 'required|string|max:255',
        ]);

        // 2. Definisikan semua kemungkinan input tambahan (sesuai name di HTML)
        $kemungkinanInput = [
            // Untuk SKU
            'bidang_usaha', 
            'nama_usaha', 
            'lokasi_usaha',
            // Untuk SKTM
            'penghasilan', 
            'jumlah_tanggungan', 
            // Untuk Kehilangan
            'barang_hilang', 
            'lokasi_kehilangan',
            // Untuk Kematian
            'hari_meninggal',
            'tgl_meninggal',
            'penyebab_kematian',
            'tempat_meninggal'
        ];

        $dataTambahan = [];

        // 3. Cek satu per satu: jika warga mengisinya, kita simpan
        foreach ($kemungkinanInput as $input) {
            if ($request->filled($input)) {
                $dataTambahan[$input] = $request->input($input);
            }
        }

        // 4. Simpan ke Database
        $ajuan = new AjuanSurat();
        $ajuan->id_warga = Auth::user()->warga->id_warga;
        $ajuan->id_jenis_surat = $request->id_jenis_surat;
        $ajuan->keperluan = $request->keperluan;
        
        // Bungkus array jadi JSON string. Contoh: {"bidang_usaha":"Kuliner"}
        // Jika kosong, biarkan null
        $ajuan->data_tambahan = count($dataTambahan) > 0 ? json_encode($dataTambahan) : null;
        
        $ajuan->status = 'BARU';
        $ajuan->save();

        return Redirect::route('warga.dashboard')->with('success', 'Ajuan surat Anda telah berhasil terkirim.');
    }
}