<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\KKImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;

class ImportKKController extends Controller
{
    public function showForm()
    {
        return view('admin.kk.import_form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xls,xlsx|max:5120'
        ]);

        DB::beginTransaction();

        try {
            Excel::import(new KKImport, $request->file('file_excel'));
            
            DB::commit();
            return Redirect::route('kk.index')->with('success', 'Data Kartu Keluarga berhasil diimpor!');

        } catch (ValidationException $e) {
            DB::rollBack();
            $errorMessage = $e->validator->errors()->first('file_excel');
            return back()->with('error', $errorMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            // Cek error duplikat entry (jika ada conflict aneh)
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->with('error', 'Gagal: Ada Nomor KK yang duplikat atau konflik data.');
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}