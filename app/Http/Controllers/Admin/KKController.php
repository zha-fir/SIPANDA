<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\KK;     // <-- 1. Impor Model KK
use App\Models\Dusun;   // <-- 2. Impor Model Dusun (KITA BUTUHKAN INI)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class KKController extends Controller
{
    /**
     * Menampilkan daftar semua KK.
     */
    public function index(Request $request)
    {
        // 1. Mulai Query dengan Eager Loading
        $query = KK::with(['dusun', 'kepalaKeluarga']);

        // 2. Logika Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->where(function($q) use ($search) {
                // Cari berdasarkan Nomor KK (langsung di tabel KK)
                $q->where('no_kk', 'like', "%{$search}%")
                  
                  // ATAU Cari berdasarkan Nama Kepala Keluarga (di tabel Warga)
                  ->orWhereHas('kepalaKeluarga', function($subQ) use ($search) {
                      $subQ->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Ambil data dengan Pagination
        $kkList = $query->paginate(10)->withQueryString();

        return view('admin.kk.index', compact('kkList'));
    }

    /**
     * Menampilkan form untuk menambah KK baru.
     */
    public function create()
    {
        // Kita perlu mengambil semua data dusun
        // untuk ditampilkan sebagai <select> (dropdown) di form.
        $dusunList = Dusun::all();

        return view('admin.kk.create', [
            'dusunList' => $dusunList
        ]);
    }

    /**
     * Menyimpan data KK baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi (termasuk validasi 'id_dusun')
        $request->validate([
            'no_kk' => 'required|string|size:16|unique:tabel_kk',
            // 'nama_kepala_keluarga' => 'required|string|max:100',
            'id_dusun' => 'required|integer|exists:tabel_dusun,id_dusun', // Pastikan id_dusun ada di tabel_dusun
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'alamat_kk' => 'required|string',
        ], [
            'no_kk.required' => 'Nomor KK wajib diisi.',
            'no_kk.unique' => 'Nomor KK ini sudah terdaftar.',
            'id_dusun.required' => 'Dusun wajib dipilih.',
        ]);

        // 2. Simpan data
        $kk = new KK();
        $kk->no_kk = $request->no_kk;
        // $kk->nama_kepala_keluarga = $request->nama_kepala_keluarga;
        $kk->id_dusun = $request->id_dusun;
        $kk->rt = $request->rt;
        $kk->rw = $request->rw;
        $kk->alamat_kk = $request->alamat_kk;
        $kk->save();

        // 3. Arahkan kembali
        return Redirect::route('kk.index')->with('success', 'Data Kartu Keluarga berhasil ditambahkan.');
    }

    public function edit(KK $kk)
    {
        // Kita juga butuh daftar dusun untuk dropdown
        $dusunList = Dusun::all();

        return view('admin.kk.edit', [
            'kk' => $kk, // Data KK yang mau diedit
            'dusunList' => $dusunList // Data semua dusun
        ]);
    }

    /**
     * Memperbarui data KK di database.
     */
    public function update(Request $request, KK $kk)
    {
        // 1. Validasi
        $request->validate([
            'no_kk' => [
                'required',
                'string',
                'size:16',
                Rule::unique('tabel_kk')->ignore($kk->id_kk, 'id_kk') // Unik, kecuali untuk dirinya sendiri
            ],
            // 'nama_kepala_keluarga' => 'required|string|max:100',
            'id_dusun' => 'required|integer|exists:tabel_dusun,id_dusun',
            'rt' => 'nullable|string|max:3',
            'rw' => 'nullable|string|max:3',
            'alamat_kk' => 'required|string',
        ]);

        // 2. Simpan perubahan
        $kk->no_kk = $request->no_kk;
        // $kk->nama_kepala_keluarga = $request->nama_kepala_keluarga;
        $kk->id_dusun = $request->id_dusun;
        $kk->rt = $request->rt;
        $kk->rw = $request->rw;
        $kk->alamat_kk = $request->alamat_kk;
        $kk->save();

        return Redirect::route('kk.index')->with('success', 'Data Kartu Keluarga berhasil diperbarui.');
    }

    /**
     * Menghapus data KK dari database.
     */
    public function destroy(KK $kk)
    {
        try {
            $kk->delete();
            return Redirect::route('kk.index')->with('success', 'Data Kartu Keluarga berhasil dihapus.');
        
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika KK masih terhubung ke data Warga
            if ($e->getCode() == '23000') {
                return Redirect::route('kk.index')->with('error', 'Gagal menghapus: Data KK ini masih terhubung dengan data warga.');
            }
            return Redirect::route('kk.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function showMembers(KK $kk)
    {
        // Load relasi 'warga' (anggota keluarga)
        // Kita juga load relasi 'dusun' dari KK untuk ditampilkan
        $kk->load('warga', 'dusun', 'kepalaKeluarga');

        // Kirim data KK (yang sudah berisi data warga) ke view
        return view('admin.kk.members', [
            'kk' => $kk
        ]);
    }
}