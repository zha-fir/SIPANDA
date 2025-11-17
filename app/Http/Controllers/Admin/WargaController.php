<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
/**
 * Menyimpan data warga baru (DAN OTOMATIS MEMBUAT AKUN LOGIN).
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
        // ... (validasi lainnya bisa Anda tambahkan kembali jika perlu)
    ], [
        'nik.required' => 'NIK wajib diisi.',
        'nik.unique' => 'NIK ini sudah terdaftar. Warga ini mungkin sudah diinput.',
        'id_kk.required' => 'Kartu Keluarga wajib dipilih.',
    ]);

    // 2. OTOMATIS Buat Akun Login (User)
    // Kita gunakan updateOrCreate untuk keamanan,
    // jika NIK sudah ada di tabel user, passwordnya akan di-reset.
    $user = User::updateOrCreate(
        ['username' => $request->nik], // Cari berdasarkan username (NIK)
        [
            'nama_lengkap' => $request->nama_lengkap,
            'password' => Hash::make('123456'), // Password default
            'role' => 'warga'
        ]
    );

    // 3. Simpan data warga
    $warga = new Warga();
    $warga->nik = $request->nik;
    $warga->nama_lengkap = $request->nama_lengkap;
    $warga->id_kk = $request->id_kk;
    $warga->id_user = $user->id_user; // <-- Hubungkan ke akun User
    $warga->tempat_lahir = $request->tempat_lahir;
    $warga->tanggal_lahir = $request->tanggal_lahir;
    $warga->jenis_kelamin = $request->jenis_kelamin;
    $warga->agama = $request->agama;
    $warga->status_perkawinan = $request->status_perkawinan;
    $warga->pekerjaan = $request->pekerjaan;
    $warga->kewarganegaraan = $request->kewarganegaraan ?? 'WNI';
    $warga->save();

    return Redirect::route('warga.index')->with('success', 'Data warga berhasil ditambahkan DAN akun login telah dibuat.');
}

    /**
     * Menampilkan form untuk mengedit data warga.
     */
    public function edit(Warga $warga)
    {
        // Kita perlu data KK untuk dropdown
        $kkList = KK::all();

        return view('admin.warga.edit', [
            'warga' => $warga, // Data warga yang mau diedit
            'kkList' => $kkList  // Data semua KK
        ]);
    }

    /**
     * Memperbarui data warga di database.
     */
    public function update(Request $request, Warga $warga)
    {
        // 1. Validasi
        $request->validate([
            'nik' => [
                'required',
                'string',
                'size:16',
                Rule::unique('tabel_warga')->ignore($warga->id_warga, 'id_warga')
            ],
            'nama_lengkap' => 'required|string|max:100',
            'id_kk' => 'required|integer|exists:tabel_kk,id_kk',
            // ... (validasi data warga lainnya) ...

            // Validasi untuk reset password (HANYA JIKA dicentang)
            'password' => 'required_if:reset_password,on|nullable|string|min:6',
        ]);

        // 2. Logika Update Akun (Reset Password)
        // Cek jika warga punya akun DAN admin mencentang reset
        if ($warga->user && $request->has('reset_password')) {
            $warga->user->password = Hash::make($request->password);
            $warga->user->save();
        }

        // 3. Update data warga
        $warga->nik = $request->nik;
        $warga->nama_lengkap = $request->nama_lengkap;
        $warga->id_kk = $request->id_kk;
        $warga->tempat_lahir = $request->tempat_lahir;
        $warga->tanggal_lahir = $request->tanggal_lahir;
        $warga->jenis_kelamin = $request->jenis_kelamin;
        $warga->agama = $request->agama;
        $warga->status_perkawinan = $request->status_perkawinan;
        $warga->pekerjaan = $request->pekerjaan;
        $warga->kewarganegaraan = $request->kewarganegaraan ?? 'WNI';
        $warga->save();

        return Redirect::route('warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    /**
     * Menghapus data warga (dan akun login terkait).
     */
    public function destroy(Warga $warga)
    {
        try {
            // Method 'booted' di Model Warga akan otomatis
            // menghapus 'user' yang terhubung sebelum warga dihapus.
            $warga->delete();
            return Redirect::route('warga.index')->with('success', 'Data warga berhasil dihapus.');
        } catch (\Exception $e) {
            return Redirect::route('warga.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}