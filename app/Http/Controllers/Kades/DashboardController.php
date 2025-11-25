<?php

namespace App\Http\Controllers\Kades;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\KK;
use App\Models\AjuanSurat;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Kartu Atas (Sama seperti sebelumnya)
        $totalWarga = Warga::count();
        $totalKK = KK::count();
        $suratMasuk = AjuanSurat::whereMonth('tanggal_ajuan', date('m'))->count();
        $suratSelesai = AjuanSurat::whereMonth('tanggal_ajuan', date('m'))->where('status', 'SELESAI')->count();

        // 2. Data untuk Grafik: Penduduk per Dusun
        // Kita ambil nama dusun dan jumlah warganya
        $dataDusun = \App\Models\Dusun::withCount('warga')->get();
        $chartDusunLabels = $dataDusun->pluck('nama_dusun'); // Label: ['Dusun A', 'Dusun B']
        $chartDusunData = $dataDusun->pluck('warga_count');  // Data: [150, 200]

        // 3. Data untuk Grafik: Pekerjaan (Top 5)
        $dataPekerjaan = Warga::select('pekerjaan', \DB::raw('count(*) as total'))
                              ->groupBy('pekerjaan')
                              ->orderByDesc('total')
                              ->limit(5)
                              ->get();
        $chartPekerjaanLabels = $dataPekerjaan->pluck('pekerjaan');
        $chartPekerjaanData = $dataPekerjaan->pluck('total');

        // 4. Data Tabel: 5 Surat Terakhir
        $suratTerbaru = AjuanSurat::with(['warga', 'jenisSurat'])
                                  ->orderBy('tanggal_ajuan', 'desc')
                                  ->limit(5)
                                  ->get();

        return view('kades.dashboard', compact(
            'totalWarga', 'totalKK', 'suratMasuk', 'suratSelesai',
            'chartDusunLabels', 'chartDusunData',
            'chartPekerjaanLabels', 'chartPekerjaanData',
            'suratTerbaru'
        ));
    }
}