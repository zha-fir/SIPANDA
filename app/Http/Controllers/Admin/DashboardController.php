<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\KK;
use App\Models\AjuanSurat;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. DATA KARTU ATAS (GLOBAL) ---
        $jumlahWarga = Warga::count();
        $jumlahKK = KK::count();
        $ajuanBaru = AjuanSurat::where('status', 'BARU')->count();
        $suratSelesai = AjuanSurat::where('status', 'SELESAI')->count();


        // --- 2. DATA DEMOGRAFI (DENGAN FILTER) ---
        $tahunLahirList = Warga::selectRaw('YEAR(tanggal_lahir) as tahun')
                               ->whereNotNull('tanggal_lahir')
                               ->distinct()
                               ->orderBy('tahun', 'desc')
                               ->pluck('tahun');

        $queryDemografi = Warga::query();

        if ($request->filled('filter_tahun')) {
            $queryDemografi->whereYear('tanggal_lahir', $request->filter_tahun);
            $labelFilter = "Kelahiran Tahun " . $request->filter_tahun;
        } else {
            $labelFilter = "Semua Umur";
        }

        $demografiLaki = (clone $queryDemografi)->where('jenis_kelamin', 'LAKI-LAKI')->count();
        $demografiPerempuan = (clone $queryDemografi)->where('jenis_kelamin', 'PEREMPUAN')->count();
        $totalFilter = $demografiLaki + $demografiPerempuan;


        // --- 3. DATA TABEL TERBARU (INI PERBAIKANNYA) ---
        // Ambil 5 surat terakhir yang diajukan untuk ditampilkan di dashboard
        $latestAjuan = AjuanSurat::with(['warga', 'jenisSurat'])
                                 ->orderBy('tanggal_ajuan', 'desc')
                                 ->take(5)
                                 ->get();


        return view('admin.dashboard', compact(
            'jumlahWarga', 'jumlahKK', 'ajuanBaru', 'suratSelesai', 
            'demografiLaki', 'demografiPerempuan', 'totalFilter', 'tahunLahirList', 'labelFilter',
            'latestAjuan' // <--- Pastikan variabel ini dikirim
        ));
    }
}