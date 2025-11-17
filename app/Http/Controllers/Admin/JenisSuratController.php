<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat; // <-- Impor Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // <-- 1. Impor Storage untuk file
use Illuminate\Validation\Rule;

class JenisSuratController extends Controller
{
    /**
     * Menampilkan daftar semua jenis surat.
     */
    public function index()
    {
        $suratList = JenisSurat::all();
        return view('admin.jenis-surat.index', [
            'suratList' => $suratList
        ]);
    }

    /**
     * Menampilkan form untuk menambah jenis surat baru.
     */
    public function create()
    {
        return view('admin.jenis-surat.create');
    }

    /**
     * Menyimpan jenis surat baru (termasuk upload file).
     */
    public function store(Request $request)
    {
        // 1. Validasi data, termasuk file
        $request->validate([
            'nama_surat' => 'required|string|max:150',
            'kode_surat' => 'nullable|string|max:20',
            'template_file' => 'required|file|mimes:docx|max:2048', // Wajib file, tipe docx, max 2MB
        ], [
            'template_file.required' => 'File template .docx wajib di-upload.',
            'template_file.mimes' => 'File template harus berekstensi .docx.',
        ]);

        $fileName = '';

        // 2. Logika Upload File
        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            
            // Buat nama file unik (kode_surat + timestamp)
            $kode = $request->kode_surat ?? 'TEMPLATE';
            $fileName = $kode . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan file ke 'storage/app/public/template_surat'
            // Hasilnya, file akan bisa diakses via '/storage/template_surat/namafile.docx'
            $file->storeAs('public/template_surat', $fileName);
        }

        // 3. Simpan data ke database
        $surat = new JenisSurat();
        $surat->nama_surat = $request->nama_surat;
        $surat->kode_surat = $request->kode_surat;
        $surat->template_file = $fileName; // Simpan nama filenya di database
        $surat->save();

        return Redirect::route('jenis-surat.index')->with('success', 'Jenis surat baru berhasil ditambahkan.');
    }

    public function edit(JenisSurat $jenisSurat)
    {
        return view('admin.jenis-surat.edit', [
            'surat' => $jenisSurat
        ]);
    }

    /**
     * Memperbarui data jenis surat di database.
     */
    public function update(Request $request, JenisSurat $jenisSurat)
    {
        // 1. Validasi
        $request->validate([
            'nama_surat' => 'required|string|max:150',
            'kode_surat' => ['nullable', 'string', 'max:20', Rule::unique('tabel_jenis_surat')->ignore($jenisSurat->id_jenis_surat, 'id_jenis_surat')],

            // File template TIDAK wajib diisi saat update
            'template_file' => 'nullable|file|mimes:docx|max:2048', 
        ]);

        // 2. Isi data yang diupdate
        $jenisSurat->nama_surat = $request->nama_surat;
        $jenisSurat->kode_surat = $request->kode_surat;

        // 3. Logika Upload File BARU (jika ada)
        if ($request->hasFile('template_file')) {
            // Hapus file template LAMA
            if ($jenisSurat->template_file) {
                Storage::delete('public/template_surat/' . $jenisSurat->template_file);
            }

            // Upload file BARU
            $file = $request->file('template_file');
            $kode = $request->kode_surat ?? 'TEMPLATE';
            $fileName = $kode . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/template_surat', $fileName);

            // Simpan nama file BARU ke database
            $jenisSurat->template_file = $fileName;
        }

        // 4. Simpan semua perubahan
        $jenisSurat->save();

        return Redirect::route('jenis-surat.index')->with('success', 'Jenis surat berhasil diperbarui.');
    }

    /**
     * Menghapus data jenis surat (dan file template-nya).
     */
    public function destroy(JenisSurat $jenisSurat)
    {
        try {
            // 1. Hapus file template dari storage
            if ($jenisSurat->template_file) {
                Storage::delete('public/template_surat/' . $jenisSurat->template_file);
            }

            // 2. Hapus data dari database
            $jenisSurat->delete();

            return Redirect::route('jenis-surat.index')->with('success', 'Jenis surat berhasil dihapus.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika jenis surat ini masih terhubung ke ajuan surat
            return Redirect::route('jenis-surat.index')->with('error', 'Gagal menghapus: Jenis surat ini masih terhubung dengan data ajuan surat.');
        }
    }
}