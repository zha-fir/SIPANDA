<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PejabatDesa; // <-- Gunakan Model baru
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PejabatDesaController extends Controller
{
    public function index()
    {
        $pejabatList = PejabatDesa::all();
        return view('admin.pejabat_desa.index', compact('pejabatList'));
    }

    public function create()
    {
        return view('admin.pejabat_desa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pejabat' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
        ]);

        $pejabat = new PejabatDesa();
        $pejabat->nama_pejabat = $request->nama_pejabat;
        $pejabat->jabatan = $request->jabatan;
        $pejabat->save();

        return Redirect::route('pejabat-desa.index')->with('success', 'Data pejabat desa berhasil ditambahkan.');
    }

    public function edit(PejabatDesa $pejabatDesa)
    {
        return view('admin.pejabat_desa.edit', ['pejabat' => $pejabatDesa]);
    }

    public function update(Request $request, PejabatDesa $pejabatDesa)
    {
        $request->validate([
            'nama_pejabat' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
        ]);

        $pejabatDesa->nama_pejabat = $request->nama_pejabat;
        $pejabatDesa->jabatan = $request->jabatan;
        $pejabatDesa->save();

        return Redirect::route('pejabat-desa.index')->with('success', 'Data pejabat desa berhasil diperbarui.');
    }

    public function destroy(PejabatDesa $pejabatDesa)
    {
        try {
            $pejabatDesa->delete();
            return Redirect::route('pejabat-desa.index')->with('success', 'Data pejabat desa berhasil dihapus.');
        } catch (\Exception $e) {
            return Redirect::route('pejabat-desa.index')->with('error', 'Gagal menghapus: Data ini mungkin masih terhubung ke ajuan surat.');
        }
    }
}