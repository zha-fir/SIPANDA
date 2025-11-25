<?php

namespace App\Http\Controllers\Kadus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warga;
use App\Models\KK;
use App\Models\AjuanSurat;

class DashboardController extends Controller
{
    /**
     * Dashboard Utama Kadus
     */
    public function index()
    {
        // 1. Ambil ID Dusun milik Kadus yang sedang login
        $idDusun = \Illuminate\Support\Facades\Auth::user()->id_dusun;

        if (!$idDusun) {
            abort(403, 'Akun Anda tidak terhubung dengan wilayah Dusun manapun.');
        }

        // 2. Statistik Kartu (Warga & KK)
        // Menggunakan whereHas untuk memfilter berdasarkan dusun dari tabel KK
        $totalWarga = Warga::whereHas('kk', function($q) use ($idDusun) {
            $q->where('id_dusun', $idDusun);
        })->count();

        $totalKK = KK::where('id_dusun', $idDusun)->count();

        // 3. Statistik Surat (Hanya warga Dusun ini)
        $querySurat = AjuanSurat::whereHas('warga.kk', function($q) use ($idDusun) {
            $q->where('id_dusun', $idDusun);
        });

        $suratMasuk = (clone $querySurat)->where('status', 'BARU')->count();
        $suratSelesai = (clone $querySurat)->where('status', 'SELESAI')->count();

        // 4. Data untuk Grafik Pie (Gender)
        $wargaLaki = Warga::whereHas('kk', function($q) use ($idDusun) {
            $q->where('id_dusun', $idDusun);
        })->where('jenis_kelamin', 'LAKI-LAKI')->count();

        $wargaPerempuan = Warga::whereHas('kk', function($q) use ($idDusun) {
            $q->where('id_dusun', $idDusun);
        })->where('jenis_kelamin', 'PEREMPUAN')->count();

        // 5. Data Tabel (5 Surat Terakhir dari Dusun ini)
        $suratTerbaru = $querySurat->with(['warga', 'jenisSurat'])
                                   ->orderBy('tanggal_ajuan', 'desc')
                                   ->limit(5)
                                   ->get();

        return view('kadus.dashboard', compact(
            'totalWarga', 'totalKK', 'suratMasuk', 'suratSelesai',
            'wargaLaki', 'wargaPerempuan', 'suratTerbaru'
        ));
    }

    /**
     * Menu "Warga Saya" (Daftar Penduduk Dusun)
     */
    public function warga(Request $request)
    {
        $idDusun = Auth::user()->id_dusun;

        $query = Warga::with('kk')
                      ->whereHas('kk', function($q) use ($idDusun) {
                          $q->where('id_dusun', $idDusun);
                      });

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        $wargaList = $query->paginate(10);

        return view('kadus.warga.index', compact('wargaList'));
    }

    /**
     * Menu "Monitoring Surat" (Khusus Dusun Ini)
     */
    public function surat()
    {
        $idDusun = Auth::user()->id_dusun;

        $ajuanList = AjuanSurat::with(['warga', 'jenisSurat'])
                               ->whereHas('warga.kk', function($q) use ($idDusun) {
                                   $q->where('id_dusun', $idDusun);
                               })
                               ->orderBy('tanggal_ajuan', 'desc')
                               ->paginate(10);

        return view('kadus.surat.index', compact('ajuanList'));
    }
}