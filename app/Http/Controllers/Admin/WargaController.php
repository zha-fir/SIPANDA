<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga; // <-- 1. Impor Model Warga
use App\Models\KK;   // <-- 2. Impor Model KK (untuk dropdown)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class WargaController extends Controller
{
    /**
     * Menampilkan daftar semua warga.
     */
    public function index()
    {
        // Ambil data warga, beserta data 'kk' yang terelasi
        // Kita juga bisa mengambil data 'dusun' melalui 'kk'
        $wargaList = Warga::with('kk.dusun')->get();

        return view('admin.warga.index', [
            'wargaList' => $wargaList
        ]);
    }

    /**
     * Menampilkan form untuk menambah warga baru.
     */
    public function create()
    {
        // Ambil semua data KK untuk dropdown
        $kkList = KK::all(); 

        return view('admin.warga.create', [
            'kkList' => $kkList
        ]);
    }

    /**
 * Menyimpan data warga baru ke database (VERSI BERSIH).
 */
public function store(Request $request)
{
    // 1. Validasi (hanya data warga)
    $request->validate([
        'nik' => 'required|string|size:16|unique:tabel_warga',
        'nama_lengkap' => 'required|string|max:100',
        'id_kk' => 'required|integer|exists:tabel_kk,id_kk',
        'tempat_lahir' => 'nullable|string|max:100',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'nullable|in:LAKI-LAKI,PEREMPUAN',
        'agama' => 'nullable|string|max:50',
        'status_perkawinan' => 'nullable|string|max:50',
        'pekerjaan' => 'nullable|string|max:100',
        'kewarganegaraan' => 'nullable|string|max:50',
    ], [
        'nik.required' => 'NIK wajib diisi.',
        'nik.unique' => 'NIK ini sudah terdaftar.',
        'id_kk.required' => 'Kartu Keluarga wajib dipilih.',
    ]);

    // 2. Simpan data warga (tanpa logika akun)
    $warga = new Warga();
    $warga->nik = $request->nik;
    $warga->nama_lengkap = $request->nama_lengkap;
    $warga->id_kk = $request->id_kk;
    // Kolom 'id_user' akan otomatis NULL (kosong)
    $warga->tempat_lahir = $request->tempat_lahir;
    $warga->tanggal_lahir = $request->tanggal_lahir;
    $warga->jenis_kelamin = $request->jenis_kelamin;
    $warga->agama = $request->agama;
    $warga->status_perkawinan = $request->status_perkawinan;
    $warga->pekerjaan = $request->pekerjaan;
    $warga->kewarganegaraan = $request->kewarganegaraan ?? 'WNI';
    $warga->save();

    return Redirect::route('warga.index')->with('success', 'Data warga berhasil ditambahkan.');
}

    // ... (biarkan fungsi show, edit, update, destroy kosong dulu) ...
}