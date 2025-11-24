<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AjuanSurat;
use App\Models\PejabatDesa; // <-- Gunakan model baru
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon; // <-- Kita butuh ini untuk format tanggal

class AjuanSuratController extends Controller
{
    /**
     * Halaman "Ajuan Surat Masuk" (Hanya status BARU).
     */
    public function index()
    {
        $ajuanList = AjuanSurat::with('warga', 'jenisSurat')
                                ->where('status', 'BARU')
                                ->orderBy('tanggal_ajuan', 'asc')
                                ->get();

        // Ambil daftar pejabat untuk dropdown di modal
        $pejabatList = PejabatDesa::all();

        return view('admin.ajuan-surat.index', compact('ajuanList', 'pejabatList'));
    }

    /**
     * Halaman "Arsip Surat" (Hanya status SELESAI atau DITOLAK).
     */
    public function arsip()
    {
        $arsipList = AjuanSurat::with('warga', 'jenisSurat', 'pejabatDesa') // <-- Ganti ke pejabatDesa
                                ->whereIn('status', ['SELESAI', 'DITOLAK'])
                                ->orderBy('tanggal_ajuan', 'desc')
                                ->get();

        return view('admin.ajuan-surat.arsip', compact('arsipList'));
    }

    /**
     * Aksi: Konfirmasi ajuan dari modal.
     */
    public function konfirmasiAjuan(Request $request, AjuanSurat $ajuan)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'id_pejabat_desa' => 'required|integer', // Pejabat 1 Wajib
            'id_pejabat_desa_2' => 'nullable|integer', // Pejabat 2 Opsional
        ]);

        $ajuan->nomor_surat = $request->nomor_surat;
        $ajuan->id_pejabat_desa = $request->id_pejabat_desa;
        $ajuan->id_pejabat_desa_2 = $request->id_pejabat_desa_2; // <-- Simpan
        $ajuan->status = 'SELESAI';
        $ajuan->catatan_penolakan = null;
        $ajuan->save();

        return Redirect::route('ajuan-surat.index')->with('success', 'Ajuan berhasil dikonfirmasi.');
    }

    /**
     * Aksi: Tolak ajuan dari modal.
     */
    public function tolakAjuan(Request $request, AjuanSurat $ajuan)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string|max:255',
        ]);

        $ajuan->catatan_penolakan = $request->catatan_penolakan;
        $ajuan->status = 'DITOLAK';
        $ajuan->nomor_surat = null; // Pastikan data surat kosong
        $ajuan->id_pejabat_desa = null;
        $ajuan->save();

        return Redirect::route('ajuan-surat.index')->with('success', 'Ajuan telah ditolak dan dipindahkan ke arsip.');
    }

    /**
     * Aksi: Cetak surat dari halaman arsip.
     */
    /**
     * Memproses dan men-download file Word (dari Arsip).
     */
    public function cetakSurat(AjuanSurat $ajuan)
    {
        // 1. Cek Status
        if ($ajuan->status != 'SELESAI') {
            return redirect()->route('ajuan-surat.arsip')->with('error', 'Surat ini tidak dapat dicetak.');
        }

        // 2. Load semua data relasi
        $ajuan->load('warga.kk.dusun', 'jenisSurat', 'pejabatDesa');

        $warga = $ajuan->warga;
        $kk = $warga->kk;
        $jenisSurat = $ajuan->jenisSurat;
        $pejabat = $ajuan->pejabatDesa;

        // 3. Cari file template
        $templatePath = Storage::path('public/template_surat/' . $jenisSurat->template_file);
        if (!Storage::exists('public/template_surat/' . $jenisSurat->template_file)) {
            return redirect()->route('ajuan-surat.arsip')->with('error', 'File template surat tidak ditemukan. Harap upload ulang di Manajemen Jenis Surat.');
        }

        try {
        $templateProcessor = new TemplateProcessor($templatePath);
        $warga = $ajuan->warga;
        $kk = $warga->kk;

        // ==========================================
        // A. DATA STATIS (Warga & Alamat)
        // ==========================================
        $templateProcessor->setValue('nama_lengkap', $warga->nama_lengkap);
        $templateProcessor->setValue('nik', $warga->nik);
        $templateProcessor->setValue('tempat_lahir', $warga->tempat_lahir);
        
        // Format Tanggal Lahir (contoh: 20 Januari 1985)
        $tglLahir = Carbon::parse($warga->tanggal_lahir)->isoFormat('D MMMM Y');
        $templateProcessor->setValue('tanggal_lahir', $tglLahir);
        
        $templateProcessor->setValue('jenis_kelamin', $warga->jenis_kelamin);
        $templateProcessor->setValue('agama', $warga->agama);
        $templateProcessor->setValue('pekerjaan', $warga->pekerjaan);
        $templateProcessor->setValue('kewarganegaraan', $warga->kewarganegaraan);
    
        
        // Alamat gabungan (sesuai template Anda yang minta ${alamat})
        $alamatLengkap = ($kk->alamat_kk ?? '-') . " RT " . ($kk->rt ?? '-') . "/RW " . ($kk->rw ?? '-') . " Desa " . ($kk->dusun->nama_dusun ?? '-');
        $templateProcessor->setValue('alamat', $alamatLengkap);


        // ==========================================
        // B. DATA HITUNGAN (Umur)
        // ==========================================
        // Hitung umur otomatis dari tanggal lahir
        $umur = Carbon::parse($warga->tanggal_lahir)->age;
        $templateProcessor->setValue('umur', $umur . ' Tahun');


        // ==========================================
        // C. DATA ADMIN (Pejabat & Surat)
        // ==========================================
        // Di template Anda tertulis ${kode_surat}, tapi di DB kita 'nomor_surat'
        $templateProcessor->setValue('kode_surat', $ajuan->nomor_surat);
        
        // Tanggal Pembuatan Surat (Hari ini)
        $templateProcessor->setValue('tanggal_pembuatan', Carbon::now()->isoFormat('D MMMM Y'));

        // Data Pejabat
        // ... (load data warga, kk, dll di atas)

            // --- DATA PEJABAT 1 (Utama/Kanan) ---
            if ($ajuan->pejabatDesa) {
                $p1 = $ajuan->pejabatDesa;
                $templateProcessor->setValue('nama_pejabat', $p1->nama_pejabat);
                $templateProcessor->setValue('jabatan_pejabat', $p1->jabatan);
                $templateProcessor->setValue('nip_pejabat', $p1->nip ?? '-');
                
                // Hitung umur P1
                $umurP1 = ($p1->tanggal_lahir) ? \Carbon\Carbon::parse($p1->tanggal_lahir)->age . ' Tahun' : '-';
                $templateProcessor->setValue('umur_pejabat', $umurP1);
            } else {
                // Isi strip jika kosong
                $templateProcessor->setValue('nama_pejabat', '-');
                $templateProcessor->setValue('jabatan_pejabat', '-');
                $templateProcessor->setValue('nip_pejabat', '-');
                $templateProcessor->setValue('umur_pejabat', '-');
            }

            // --- DATA PEJABAT 2 (Tambahan/Kiri) ---
            if ($ajuan->pejabatDesa2) {
                $p2 = $ajuan->pejabatDesa2;
                $templateProcessor->setValue('nama_pejabat_2', $p2->nama_pejabat);
                $templateProcessor->setValue('jabatan_pejabat_2', $p2->jabatan);
                $templateProcessor->setValue('nip_pejabat_2', $p2->nip ?? '-');
                
                // Hitung umur P2
                $umurP2 = ($p2->tanggal_lahir) ? \Carbon\Carbon::parse($p2->tanggal_lahir)->age . ' Tahun' : '-';
                $templateProcessor->setValue('umur_pejabat_2', $umurP2);
            } else {
                // Jika tidak dipilih, kosongkan (String kosong agar di Word bersih)
                $templateProcessor->setValue('nama_pejabat_2', '');
                $templateProcessor->setValue('jabatan_pejabat_2', '');
                $templateProcessor->setValue('nip_pejabat_2', '');
                $templateProcessor->setValue('umur_pejabat_2', '');
            }
            
            // ... (lanjut download)


        // ==========================================
        // D. DATA DINAMIS (JSON) - "Tas Ajaib"
        // ==========================================
        // Bongkar JSON
        $extra = json_decode($ajuan->data_tambahan, true) ?? [];
            //SKU   
            $templateProcessor->setValue('bidang_usaha', $extra['bidang_usaha'] ?? '-');
            $templateProcessor->setValue('nama_usaha', $extra['nama_usaha'] ?? '-');
            $templateProcessor->setValue('lokasi_usaha', $extra['lokasi_usaha'] ?? '-');
            
            // SKTM
            $templateProcessor->setValue('penghasilan', $extra['penghasilan'] ?? '-');
            $templateProcessor->setValue('jumlah_tanggungan', $extra['jumlah_tanggungan'] ?? '-');
            
            // Kehilangan
            $templateProcessor->setValue('barang_hilang', $extra['barang_hilang'] ?? '-');
            $templateProcessor->setValue('lokasi_kehilangan', $extra['lokasi_kehilangan'] ?? '-');
            
            // Kematian
            $templateProcessor->setValue('hari_meninggal', $extra['hari_meninggal'] ?? '-');
            $templateProcessor->setValue('tgl_meninggal', $extra['tgl_meninggal'] ?? '-');
            $templateProcessor->setValue('penyebab_kematian', $extra['penyebab_kematian'] ?? '-');
            $templateProcessor->setValue('tempat_meninggal', $extra['tempat_meninggal'] ?? '-');

            $templateProcessor->setValue('nama_pemilik_rumah', $extra['nama_pemilik_rumah'] ?? '-');

            // Mapping Alamat Pejabat (Hardcode Alamat Kantor Desa agar rapi)
            // Atau Anda bisa ambil dari $pejabat->alamat jika nanti Anda menambahkan kolom alamat di tabel pejabat
            $templateProcessor->setValue('alamat_pejabat', 'Desa Panggulo, Kec. Botupingge');

        
        // 5. Download File
        $outputFileName = $ajuan->jenisSurat->kode_surat . '_' . str_replace(' ', '_', $warga->nama_lengkap) . '.docx';
        $tempPath = storage_path('app/temp/' . $outputFileName);
        
        if (!Storage::exists('temp')) { Storage::makeDirectory('temp'); }
        
        $templateProcessor->saveAs($tempPath);
        return response()->download($tempPath)->deleteFileAfterSend(true);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }


    }

    /**
     * Aksi: Menampilkan halaman detail (dari Arsip).
     */
    public function detailSurat(AjuanSurat $ajuan)
    {
        $ajuan->load('warga.kk.dusun', 'jenisSurat', 'pejabatDesa');
        return view('admin.ajuan-surat.detail', compact('ajuan'));
    }
}