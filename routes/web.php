<?php

use Illuminate\Support\Facades\Route;
// Import semua Controller
use App\Http\Controllers\Citizen\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DusunController;
use App\Http\Controllers\Admin\KKController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\PejabatDesaController;
use App\Http\Controllers\Admin\ImportWargaController;
use App\Http\Controllers\Admin\ImportKKController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AjuanSuratController as AdminAjuanSuratController;
use App\Http\Controllers\Citizen\AjuanSuratController as CitizenAjuanSuratController;
use App\Http\Controllers\Citizen\ProfileController;
use App\Http\Controllers\Kades\DashboardController as KadesDashboard;
use App\Http\Controllers\Kades\MonitoringController;
use App\Http\Controllers\Kades\PendudukController;
use App\Http\Controllers\Kades\LaporanController;
use App\Http\Controllers\Kadus\DashboardController as KadusDashboard;

// ==========================================================
// 1. ZONA PUBLIK (TIDAK PERLU LOGIN)
// ==========================================================

// Jika buka halaman utama, lempar ke login
Route::get('/', function () {
    return redirect()->route('warga.login.form');
});

// Halaman Login & Proses Login
// Kita gunakan middleware 'guest' agar yang SUDAH login tidak bisa masuk sini lagi
Route::middleware('guest')->prefix('warga')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('warga.login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('warga.login.submit');
});

// ==========================================================
// 2. ZONA TERKUNCI (WAJIB LOGIN)
// ==========================================================

Route::middleware('auth')->group(function () {

    // --- RUTE UMUM (Bisa diakses semua yang login) ---
    Route::post('/warga/logout', [AuthController::class, 'logout'])->name('warga.logout');


    // --- ZONA KHUSUS WARGA ---
    Route::prefix('warga')->middleware('role:warga')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('warga.dashboard');
        
        // Ajuan Surat
        Route::get('/ajuan-surat', [CitizenAjuanSuratController::class, 'create'])->name('warga.ajuan.create');
        Route::post('/ajuan-surat', [CitizenAjuanSuratController::class, 'store'])->name('warga.ajuan.store');
        Route::get('/riwayat-ajuan', [CitizenAjuanSuratController::class, 'history'])->name('warga.ajuan.history');
        
        // Profil & Password
        Route::get('/profil/password', [ProfileController::class, 'editPassword'])->name('warga.password.edit');
        Route::post('/profil/password', [ProfileController::class, 'updatePassword'])->name('warga.password.update');
    });


    // --- ZONA KHUSUS ADMIN ---
    // (Kita izinkan 'kades' juga masuk admin dashboard jika diperlukan, atau hapus ',kades' jika admin eksklusif)
    Route::prefix('admin')->middleware('role:admin,kades')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // CRUD Data Master
        Route::resource('dusun', DusunController::class);
        Route::resource('kk', KKController::class);
        Route::resource('warga', WargaController::class);
        Route::resource('jenis-surat', JenisSuratController::class);
        Route::resource('pejabat-desa', PejabatDesaController::class);
        
        // Manajemen User (Hanya Admin & Kades)
        Route::resource('users', UserController::class);

        // Fitur Spesial
        Route::get('kk/{kk}/members', [KKController::class, 'showMembers'])->name('kk.members');
        
        // Import Excel
        Route::get('import-warga', [ImportWargaController::class, 'showForm'])->name('admin.warga.import.form');
        Route::post('import-warga', [ImportWargaController::class, 'import'])->name('admin.warga.import.submit');
        Route::get('import-kk', [ImportKKController::class, 'showForm'])->name('admin.kk.import.form');
        Route::post('import-kk', [ImportKKController::class, 'import'])->name('admin.kk.import.submit');

        // Layanan Surat
        Route::resource('ajuan-surat', AdminAjuanSuratController::class);
        Route::get('arsip-surat', [AdminAjuanSuratController::class, 'arsip'])->name('ajuan-surat.arsip');
        
        // Aksi Surat
        Route::post('ajuan-surat/{ajuan}/tolak', [AdminAjuanSuratController::class, 'tolakAjuan'])->name('ajuan-surat.tolak');
        Route::post('ajuan-surat/{ajuan}/konfirmasi', [AdminAjuanSuratController::class, 'konfirmasiAjuan'])->name('ajuan-surat.konfirmasi');
        Route::get('ajuan-surat/{ajuan}/cetak', [AdminAjuanSuratController::class, 'cetakSurat'])->name('ajuan-surat.cetak');
        Route::get('ajuan-surat/{ajuan}/detail', [AdminAjuanSuratController::class, 'detailSurat'])->name('ajuan-surat.detail');
    });


    // --- ZONA KHUSUS KEPALA DESA (KADES) ---
    Route::prefix('kades')->middleware('role:kades')->group(function () {
        Route::get('/dashboard', [KadesDashboard::class, 'index'])->name('kades.dashboard');
        
        // Monitoring
        Route::get('/monitoring-surat', [MonitoringController::class, 'index'])->name('kades.monitoring.index');
        Route::get('/monitoring-surat/{id}', [MonitoringController::class, 'show'])->name('kades.monitoring.show');
        
        // Data Penduduk (Read Only)
        Route::get('/penduduk', [PendudukController::class, 'index'])->name('kades.penduduk.index');
        Route::get('/penduduk/{id}', [PendudukController::class, 'show'])->name('kades.penduduk.show');
        
        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('kades.laporan.index');
        Route::get('/laporan/penduduk/cetak', [LaporanController::class, 'cetakPenduduk'])->name('kades.laporan.cetak-penduduk');
        Route::get('/laporan/surat/cetak', [LaporanController::class, 'cetakSurat'])->name('kades.laporan.cetak-surat');
    });


    // --- ZONA KHUSUS KEPALA DUSUN (KADUS) ---
    Route::prefix('kadus')->middleware('role:kadus')->group(function () {
        Route::get('/dashboard', [KadusDashboard::class, 'index'])->name('kadus.dashboard');
        Route::get('/warga', [KadusDashboard::class, 'warga'])->name('kadus.warga');
        Route::get('/surat', [KadusDashboard::class, 'surat'])->name('kadus.surat');
    });

});