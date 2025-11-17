<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat; // <-- Impor Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // <-- 1. Impor Storage untuk file

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

    // ... (biarkan fungsi show, edit, update, destroy kosong dulu) ...
}