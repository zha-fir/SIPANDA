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
        // Ambil data statistik
        $jumlahWarga = Warga::count();
        $jumlahKK = KK::count();
        $ajuanBaru = AjuanSurat::where('status', 'BARU')->count();
        $suratSelesai = AjuanSurat::where('status', 'SELESAI')->count();

        // Kirim data ke view
        return view('admin.dashboard', compact(
            'jumlahWarga', 
            'jumlahKK', 
            'ajuanBaru', 
            'suratSelesai'
        ));
    }
}