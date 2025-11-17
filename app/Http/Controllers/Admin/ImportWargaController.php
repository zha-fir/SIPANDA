<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\WargaImport; // <-- 1. Impor class WargaImport
use Maatwebsite\Excel\Facades\Excel; // <-- 2. Impor fasad Excel
use Illuminate\Support\Facades\DB; // <-- 3. Impor DB untuk Transaction
use Illuminate\Support\Facades\Log; // <-- 4. Impor Log untuk error
use Illuminate\Validation\ValidationException; // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\Redirect;

class ImportWargaController extends Controller
{
    /**
     * Menampilkan halaman form upload Excel.
     */
    public function showForm()
    {
        return view('admin.warga.import_form');
    }

    /**
     * Memproses file Excel yang di-upload.
     */
    public function import(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'file_excel' => 'required|mimes:xls,xlsx|max:5120' // Max 5MB
        ]);

        // 2. Mulai Transaksi Database
        // Ini akan "mengunci" database. Jika ada 1 error, semua import dibatalkan.
        DB::beginTransaction();

        try {
            // 3. Proses Import
            Excel::import(new WargaImport, $request->file('file_excel'));

            // 4. Jika sukses, commit transaksi
            DB::commit();

            return Redirect::route('warga.index')->with('success', 'Data warga berhasil diimpor dan akun login telah dibuat!');

        } // --- KODE BARU DIMULAI DARI SINI ---
            catch (ValidationException $e) {
                // 5. Menangkap error spesifik yang kita buat di WargaImport
                DB::rollBack();

                // Ambil pesan error spesifik kita
                $errorMessage = $e->validator->errors()->first('file_excel');

                return back()->with('error', $errorMessage);

            } catch (\Exception $e) {
                // 6. Menangkap error umum lainnya (misal: file rusak)
                DB::rollBack();
                Log::error('Error saat import Excel: ' . $e->getMessage());

                $errorMessage = 'Terjadi kesalahan umum: ' . $e->getMessage();
                return back()->with('error', $errorMessage);
            }
            // --- AKHIR KODE BARU ---
                    
                }
}