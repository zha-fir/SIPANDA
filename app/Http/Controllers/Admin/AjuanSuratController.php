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
            return redirect()->route('ajuan-surat.arsip')->with('error', 'Surat ini tidak dapat dicetak (status belum SELESAI).');
        }

        // 2. Load Data
        $ajuan->load('warga.kk.dusun', 'jenisSurat', 'pejabatDesa', 'pejabatDesa2');

        $warga = $ajuan->warga;
        $kk = $warga->kk;
        $jenisSurat = $ajuan->jenisSurat;

        // 3. Cek File Template
        $templatePath = Storage::path('public/template_surat/' . $jenisSurat->template_file);
        if (!Storage::exists('public/template_surat/' . $jenisSurat->template_file)) {
            return redirect()->route('ajuan-surat.arsip')->with('error', 'File template surat tidak ditemukan.');
        }

        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            // ==========================================
            // A. DATA STATIS WARGA
            // ==========================================
            $templateProcessor->setValue('nama_lengkap', strtoupper($warga->nama_lengkap));
            $templateProcessor->setValue('nik', $warga->nik);
            $templateProcessor->setValue('tempat_lahir', ucwords(strtolower($warga->tempat_lahir)));
            $templateProcessor->setValue('tanggal_lahir', \Carbon\Carbon::parse($warga->tanggal_lahir)->isoFormat('D MMMM Y'));
            $templateProcessor->setValue('jenis_kelamin', ucwords(strtolower($warga->jenis_kelamin)));
            $templateProcessor->setValue('agama', ucwords(strtolower($warga->agama)));
            $templateProcessor->setValue('pekerjaan', ucwords(strtolower($warga->pekerjaan)));
            $templateProcessor->setValue('kewarganegaraan', strtoupper($warga->kewarganegaraan));

            // Alamat & Dusun
            $alamatKecil = ucwords(strtolower($kk->alamat_kk ?? '-'));
            $namaDusun   = ucwords(strtolower($kk->dusun->nama_dusun ?? 'N/A'));
            
            $templateProcessor->setValue('alamat_kk', $alamatKecil);
            $templateProcessor->setValue('rt', $kk->rt);
            $templateProcessor->setValue('rw', $kk->rw);
            $templateProcessor->setValue('nama_dusun', $namaDusun); // PENTING UNTUK KALIMAT "Kepala Dusun..."

            $alamatLengkap = $alamatKecil . " RT " . ($kk->rt ?? '-') . "/RW " . ($kk->rw ?? '-') . " Desa " . $namaDusun;
            $templateProcessor->setValue('alamat', $alamatLengkap);

            // ==========================================
            // B. DATA HITUNGAN (UMUR)
            // ==========================================
            $umur = \Carbon\Carbon::parse($warga->tanggal_lahir)->age;
            $templateProcessor->setValue('umur', $umur . ' Tahun');

            // ==========================================
            // C. DATA ADMIN (SURAT & PEJABAT)
            // ==========================================
            $templateProcessor->setValue('kode_surat', $ajuan->nomor_surat);
            $templateProcessor->setValue('tanggal_pembuatan', \Carbon\Carbon::now()->isoFormat('D MMMM Y'));
            
            // Alamat Kantor Desa (Header Surat)
            $templateProcessor->setValue('alamat_pejabat', 'Desa Panggulo, Kec. Botupingge');

            // Pejabat 1 (Kanan)
            if ($ajuan->pejabatDesa) {
                $p1 = $ajuan->pejabatDesa;
                $templateProcessor->setValue('nama_pejabat', strtoupper($p1->nama_pejabat));
                $templateProcessor->setValue('jabatan_pejabat', ucwords(strtolower($p1->jabatan)));
                $templateProcessor->setValue('nip_pejabat', $p1->nip ?? '-');
                
                $umurP1 = ($p1->tanggal_lahir) ? \Carbon\Carbon::parse($p1->tanggal_lahir)->age . ' Tahun' : '-';
                $templateProcessor->setValue('umur_pejabat', $umurP1);
            } else {
                $templateProcessor->setValue('nama_pejabat', '-');
                $templateProcessor->setValue('jabatan_pejabat', '-');
                $templateProcessor->setValue('nip_pejabat', '-');
                $templateProcessor->setValue('umur_pejabat', '-');
            }

            // Pejabat 2 (Kiri - Opsional)
            if ($ajuan->pejabatDesa2) {
                $p2 = $ajuan->pejabatDesa2;
                $templateProcessor->setValue('nama_pejabat_2', strtoupper($p2->nama_pejabat));
                $templateProcessor->setValue('jabatan_pejabat_2', ucwords(strtolower($p2->jabatan)));
                $templateProcessor->setValue('nip_pejabat_2', $p2->nip ?? '-');
                
                $umurP2 = ($p2->tanggal_lahir) ? \Carbon\Carbon::parse($p2->tanggal_lahir)->age . ' Tahun' : '-';
                $templateProcessor->setValue('umur_pejabat_2', $umurP2);
            } else {
                // Kosongkan string agar bersih di Word
                $templateProcessor->setValue('nama_pejabat_2', '');
                $templateProcessor->setValue('jabatan_pejabat_2', '');
                $templateProcessor->setValue('nip_pejabat_2', '');
                $templateProcessor->setValue('umur_pejabat_2', '');
            }

            // ==========================================
            // D. DATA DINAMIS (JSON)
            // ==========================================
            $extra = json_decode($ajuan->data_tambahan, true) ?? [];

            // SKU
            $templateProcessor->setValue('bidang_usaha', $extra['bidang_usaha'] ?? '-');
            $templateProcessor->setValue('nama_usaha', $extra['nama_usaha'] ?? '-');
            $templateProcessor->setValue('lokasi_usaha', $extra['lokasi_usaha'] ?? '-');
            
            // SKTM
            $templateProcessor->setValue('penghasilan', $extra['penghasilan'] ?? '-');
            // Hitung Tanggungan
            $totalAnggota = \App\Models\Warga::where('id_kk', $kk->id_kk)->count();
            $jumlahTanggungan = $totalAnggota > 0 ? ($totalAnggota - 1) : 0;
            $templateProcessor->setValue('jumlah_tanggungan', $jumlahTanggungan . ' Orang');
            
            // Kehilangan & Lainnya
            $templateProcessor->setValue('barang_hilang', $extra['barang_hilang'] ?? '-');
            $templateProcessor->setValue('lokasi_kehilangan', $extra['lokasi_kehilangan'] ?? '-');
            $templateProcessor->setValue('hari_meninggal', $extra['hari_meninggal'] ?? '-');
            $templateProcessor->setValue('tgl_meninggal', $extra['tgl_meninggal'] ?? '-');
            $templateProcessor->setValue('penyebab_kematian', $extra['penyebab_kematian'] ?? '-');
            $templateProcessor->setValue('tempat_meninggal', $extra['tempat_meninggal'] ?? '-');
            $templateProcessor->setValue('nama_pemilik_rumah', $extra['nama_pemilik_rumah'] ?? '-');
            
            $templateProcessor->setValue('keperluan', $ajuan->keperluan);

            // ==========================================
            // E. LOGIKA TABEL KELUARGA (LOOPING)
            // ==========================================
            if ($warga->id_kk) {
                $anggotaKeluarga = \App\Models\Warga::where('id_kk', $warga->id_kk)
                                                    ->orderBy('tanggal_lahir', 'asc')
                                                    ->get();
                $dataTabel = [];
                $no = 1;
                foreach ($anggotaKeluarga as $anggota) {
                    $tglLahirAnggota = $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->isoFormat('D MMMM Y') : '-';
                    
                    // Masukkan ke array
                    $dataTabel[] = [
                        't_no'   => $no++,
                        't_nama' => strtoupper($anggota->nama_lengkap),
                        't_ttl'  => ucwords(strtolower($anggota->tempat_lahir ?? '-')) . ', ' . $tglLahirAnggota,
                        't_jk'   => ucwords(strtolower($anggota->jenis_kelamin)),
                        't_kk'   => $kk->no_kk,
                        't_nik'  => $anggota->nik,
                        't_hub'  => $anggota->status_dalam_keluarga ?? '-'
                    ];
                }

                // Clone Row (Hanya jika ada data)
                try {
                    if(count($dataTabel) > 0) {
                        $templateProcessor->cloneRowAndSetValues('t_no', $dataTabel);
                    }
                } catch (\Exception $e) {
                    // Abaikan jika template tidak punya tabel (misal SKU)
                }
            }

            // 5. Save & Download
            $namaWargaClean = str_replace(' ', '_', $warga->nama_lengkap);
            $outputFileName = $jenisSurat->kode_surat . '_' . $namaWargaClean . '_' . date('d-m-Y') . '.docx';
            $tempPath = storage_path('app/temp/' . $outputFileName);
            
            if (!Storage::exists('temp')) { Storage::makeDirectory('temp'); }
            
            $templateProcessor->saveAs($tempPath);
            return response()->download($tempPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal generate surat: ' . $e->getMessage());
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