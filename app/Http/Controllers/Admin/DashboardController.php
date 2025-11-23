<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\KK;
use App\Models\AjuanSurat;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama (Kartu Atas)
        $jumlahWarga = Warga::count();
        $jumlahKK = KK::count();
        $ajuanBaru = AjuanSurat::where('status', 'BARU')->count();
        $suratSelesai = AjuanSurat::where('status', 'SELESAI')->count();

        // 2. Statistik Demografi (Untuk Grafik/Bar)
        $wargaLaki = Warga::where('jenis_kelamin', 'LAKI-LAKI')->count();
        $wargaPerempuan = Warga::where('jenis_kelamin', 'PEREMPUAN')->count();
        
        // Hitung persentase (cegah error division by zero)
        $persenLaki = $jumlahWarga > 0 ? round(($wargaLaki / $jumlahWarga) * 100) : 0;
        $persenPerempuan = $jumlahWarga > 0 ? round(($wargaPerempuan / $jumlahWarga) * 100) : 0;

        // 3. Tabel Aktivitas Terbaru (Ambil 5 surat terakhir)
        $latestAjuan = AjuanSurat::with(['warga', 'jenisSurat'])
                                 ->orderBy('tanggal_ajuan', 'desc')
                                 ->take(3)
                                 ->get();

        return view('admin.dashboard', compact(
            'jumlahWarga', 'jumlahKK', 'ajuanBaru', 'suratSelesai',
            'wargaLaki', 'wargaPerempuan', 'persenLaki', 'persenPerempuan',
            'latestAjuan'
        ));
    }
}