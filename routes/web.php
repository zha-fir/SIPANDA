<?php
use App\Http\Controllers\Admin\PejabatDesaController;
use App\Http\Controllers\Admin\DusunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KKController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\AjuanSuratController as AdminAjuanSuratController;
use App\Http\Controllers\Citizen\AuthController;
use App\Http\Controllers\Admin\ImportWargaController;
use App\Http\Controllers\Citizen\AjuanSuratController as CitizenAjuanSuratController;
use App\Http\Controllers\Admin\DashboardController;

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

    // Rute yang HANYA bisa diakses setelah login
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('warga.dashboard');

        // --- TAMBAHKAN RUTE INI ---
        Route::get('/ajuan-surat', [CitizenAjuanSuratController::class, 'create'])->name('warga.ajuan.create');
        Route::post('/ajuan-surat', [CitizenAjuanSuratController::class, 'store'])->name('warga.ajuan.store');
        // --- AKHIR PENAMBAHAN ---
    });
});
// --- AKHIR GRUP RUTE WARGA ---

    // --- TAMBAHKAN INI ---
    // Grup Rute untuk Admin, dengan prefix 'admin'
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('pejabat-desa', PejabatDesaController::class);
        
        // Ini akan otomatis membuat semua URL untuk CRUD Dusun
        // seperti: admin/dusun, admin/dusun/create, admin/dusun/1/edit, dll.
        Route::resource('dusun', DusunController::class);
        Route::resource('kk', KKController::class);
        Route::resource('warga', WargaController::class);
        Route::resource('jenis-surat', JenisSuratController::class);

        Route::get('kk/{kk}/members', [KKController::class, 'showMembers'])->name('kk.members');


        Route::get('arsip-surat', [AdminAjuanSuratController::class, 'arsip'])->name('ajuan-surat.arsip');

        Route::get('ajuan-surat/{ajuan}/proses', [AdminAjuanSuratController::class, 'prosesAjuan'])
            ->name('ajuan-surat.proses');

        Route::post('ajuan-surat/{ajuan}/tolak', [AdminAjuanSuratController::class, 'tolakAjuan'])
            ->name('ajuan-surat.tolak');

        Route::resource('ajuan-surat', AdminAjuanSuratController::class);

        // Rute untuk menampilkan halaman form import (GET)
        Route::get('import-warga', [ImportWargaController::class, 'showForm'])
            ->name('admin.warga.import.form');

        // Rute untuk memproses file excel yang di-upload (POST)
        Route::post('import-warga', [ImportWargaController::class, 'import'])
            ->name('admin.warga.import.submit');
});
