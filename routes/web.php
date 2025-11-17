<?php
use App\Http\Controllers\Admin\DusunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KKController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\AjuanSuratController;
use App\Http\Controllers\Citizen\AuthController;
use App\Http\Controllers\Admin\ImportWargaController;

// Rute Halaman Utama (nanti bisa jadi landing page)
Route::get('/', function () {
    // Otomatis arahkan halaman utama ke halaman login
    return redirect()->route('warga.login.form');
});

// --- TAMBAHKAN GRUP RUTE INI UNTUK WARGA ---
Route::prefix('warga')->group(function () {
    // Rute untuk menampilkan halaman login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('warga.login.form');
    // Rute untuk memproses data login
    Route::post('/login', [AuthController::class, 'login'])->name('warga.login.submit');
    // Rute untuk logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('warga.logout');

    // Rute untuk dashboard warga (HANYA bisa diakses setelah login)
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('warga.dashboard')->middleware('auth');
});
// --- AKHIR GRUP RUTE WARGA ---

// --- TAMBAHKAN INI ---
// Grup Rute untuk Admin, dengan prefix 'admin'
Route::prefix('admin')->group(function () {
    
    // Ini akan otomatis membuat semua URL untuk CRUD Dusun
    // seperti: admin/dusun, admin/dusun/create, admin/dusun/1/edit, dll.
    Route::resource('dusun', DusunController::class);
    Route::resource('kk', KKController::class);
    Route::resource('warga', WargaController::class);
    Route::resource('jenis-surat', JenisSuratController::class);

    Route::get('arsip-surat', [AjuanSuratController::class, 'arsip'])->name('ajuan-surat.arsip');
    // Rute untuk memproses (generate) surat
    Route::get('ajuan-surat/{ajuan}/proses', [AjuanSuratController::class, 'prosesAjuan'])
        ->name('ajuan-surat.proses');
    
    // Rute untuk menolak ajuan
    Route::post('ajuan-surat/{ajuan}/tolak', [AjuanSuratController::class, 'tolakAjuan'])
        ->name('ajuan-surat.tolak');
    Route::resource('ajuan-surat', AjuanSuratController::class);

    // Rute untuk menampilkan halaman form import (GET)
    Route::get('import-warga', [ImportWargaController::class, 'showForm'])
         ->name('admin.warga.import.form');

    // Rute untuk memproses file excel yang di-upload (POST)
    Route::post('import-warga', [ImportWargaController::class, 'import'])
         ->name('admin.warga.import.submit');
});
