<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AjuanSurat;
use App\Models\KK; // Kita perlu ini untuk ambil data KK
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengakses file template
use PhpOffice\PhpWord\TemplateProcessor; // <--- INI PENTING

class AjuanSuratController extends Controller
{
    /**
     * Menampilkan daftar ajuan surat yang masih 'BARU'.
     */
    public function index()
    {
        // Ambil data ajuan, tapi hanya yang statusnya 'BARU'
        // 'with' untuk eager loading (mengambil data relasi Warga dan JenisSurat)
        $ajuanList = AjuanSurat::with('warga', 'jenisSurat')
                                ->where('status', 'BARU')
                                ->orderBy('tanggal_ajuan', 'asc') // Tampilkan yang paling lama di atas
                                ->get();

        return view('admin.ajuan-surat.index', [
            'ajuanList' => $ajuanList
        ]);
    }

    /**
     * Menampilkan halaman arsip (surat SELESAI atau DITOLAK).
     */
    public function arsip()
    {
        // Ambil data ajuan yang sudah tidak 'BARU'
        $arsipList = AjuanSurat::with('warga', 'jenisSurat')
                                ->whereIn('status', ['SELESAI', 'DITOLAK'])
                                ->orderBy('tanggal_ajuan', 'desc') // Tampilkan yang terbaru di atas
                                ->get();

        return view('admin.ajuan-surat.arsip', [
            'arsipList' => $arsipList
        ]);
    }

    // ... (method index() dan arsip() yang sudah ada) ...

/**
 * Memproses ajuan, men-generate surat, dan men-download file.
 */
    public function prosesAjuan(AjuanSurat $ajuan)
    {
        // 1. Ambil data yang diperlukan
        // Kita pakai 'find' untuk memastikan data terbaru (terutama relasinya)
        $ajuan = AjuanSurat::with('warga.kk.dusun', 'jenisSurat')->find($ajuan->id_ajuan);

        if (!$ajuan) {
            return redirect()->route('ajuan-surat.index')->with('error', 'Ajuan tidak ditemukan.');
        }

        $warga = $ajuan->warga;
        $kk = $warga->kk;
        $jenisSurat = $ajuan->jenisSurat;

        // 2. Tentukan path ke file template
        // 'storage/app/public/template_surat/namafile.docx'
        $templatePath = Storage::path('public/template_surat/' . $jenisSurat->template_file);

        if (!Storage::exists('public/template_surat/' . $jenisSurat->template_file)) {
            return redirect()->route('ajuan-surat.index')->with('error', 'File template tidak ditemukan.');
        }

        // 3. Buat nomor surat (contoh sederhana)
        $nomorSurat = $jenisSurat->kode_surat . '/' . $ajuan->id_ajuan . '/' . date('m/Y');

        // 4. Proses template menggunakan PHPWord
        try {
            $templateProcessor = new TemplateProcessor($templatePath);

            // --- PENGISIAN DATA (Template Variable) ---
            // Ini harus cocok dengan template .docx Anda: ${nama_lengkap}, ${nik}, dll.
            $templateProcessor->setValue('nama_lengkap', $warga->nama_lengkap);
            $templateProcessor->setValue('nik', $warga->nik);
            $templateProcessor->setValue('tempat_lahir', $warga->tempat_lahir);
            $templateProcessor->setValue('tanggal_lahir', \Carbon\Carbon::parse($warga->tanggal_lahir)->isoFormat('D MMMM Y'));
            $templateProcessor->setValue('jenis_kelamin', $warga->jenis_kelamin);
            $templateProcessor->setValue('agama', $warga->agama);
            $templateProcessor->setValue('pekerjaan', $warga->pekerjaan);

            // Mengambil data dari KK
            $templateProcessor->setValue('alamat_kk', $kk->alamat_kk);
            $templateProcessor->setValue('rt', $kk->rt);
            $templateProcessor->setValue('rw', $kk->rw);

            // Mengambil data dari Dusun (melalui KK)
            $templateProcessor->setValue('nama_dusun', $kk->dusun->nama_dusun ?? 'N/A');

            // Data Surat
            $templateProcessor->setValue('nomor_surat', $nomorSurat);

            // 5. Simpan file hasil generate
            $outputFileName = 'Surat_' . $jenisSurat->kode_surat . '_' . $warga->nik . '_' . time() . '.docx';
            $outputPath = storage_path('app/public/hasil_surat/' . $outputFileName);

            // Pastikan folder 'hasil_surat' ada
            if (!Storage::exists('public/hasil_surat')) {
                Storage::makeDirectory('public/hasil_surat');
            }

            $templateProcessor->saveAs($outputPath);

            // 6. Update database
            $ajuan->status = 'SELESAI';
            $ajuan->nomor_surat_lengkap = $nomorSurat;
            $ajuan->file_hasil = 'hasil_surat/' . $outputFileName; // Simpan path relatif
            $ajuan->save();

            // 7. Kembalikan file untuk di-download
            return response()->download($outputPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Jika ada error (misal: template tidak ditemukan, variabel salah)
            return redirect()->route('ajuan-surat.index')->with('error', 'Terjadi kesalahan saat generate surat: ' . $e->getMessage());
        }
    }

    /**
     * Menolak ajuan surat.
     */
    public function tolakAjuan(Request $request, AjuanSurat $ajuan)
    {
        // Update status dan catatan penolakan
        $ajuan->status = 'DITOLAK';
        $ajuan->catatan_penolakan = $request->input('catatan_penolakan');
        $ajuan->save();

        return redirect()->route('ajuan-surat.index')->with('success', 'Ajuan surat telah ditolak.');
    }
    }