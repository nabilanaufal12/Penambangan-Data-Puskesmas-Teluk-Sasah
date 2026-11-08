<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ExecutiveController;

/*
|--------------------------------------------------------------------------
| Web Routes - PRABOTIK (Puskesmas Teluk Sasah)
|--------------------------------------------------------------------------
| Sistem dipisahkan menjadi 2 Portal Aplikasi Terpisah dengan Proteksi Sandi:
| 1. Gateway Pintu Utama: Pemilihan Portal & Otentikasi Sandi (/)
| 2. PRABOTIK Farmasi: Khusus Tenaga Farmasi & Gudang Obat (/farmasi/...)
| 3. PRABOTIK Eksekutif: Khusus Kepala Puskesmas Ber-Password (/kepala/...)
*/

// =========================================================================
// 1. GATEWAY PINTU MASUK PORTAL UTAMA & OTENTIKASI
// =========================================================================
Route::get('/', [GatewayController::class, 'index'])->name('gateway');
Route::post('/login/kepala', [GatewayController::class, 'loginKepala'])->name('login.kepala');
Route::post('/login/farmasi', [GatewayController::class, 'loginFarmasi'])->name('login.pharmacy');
Route::get('/logout', [GatewayController::class, 'logout'])->name('logout');


// =========================================================================
// 2. APLIKASI KHUSUS BAGIAN FARMASI & GUDANG OBAT (Apt. Siti Rahmawati)
// =========================================================================
Route::prefix('farmasi')->name('pharmacy.')->middleware('role.pharmacy')->group(function () {
    Route::get('/', [PharmacyController::class, 'index'])->name('dashboard');
    
    // Pusat Entri Data Faskes (4 Modul)
    Route::get('/entri-data', [PharmacyController::class, 'dataEntry'])->name('entry');
    Route::post('/entri-data/lplpo', [PharmacyController::class, 'storeLplpo'])->name('entry.lplpo');
    Route::post('/entri-data/penyakit', [PharmacyController::class, 'storeDisease'])->name('entry.disease');
    Route::post('/entri-data/kadaluarsa', [PharmacyController::class, 'storeExpired'])->name('entry.expired');
    Route::post('/entri-data/obat', [PharmacyController::class, 'storeMedicine'])->name('entry.medicine');

    // Pengadaan & Logistik
    Route::get('/rencana-pengadaan', [PharmacyController::class, 'procurement'])->name('procurement');
    Route::get('/katalog-obat', [PharmacyController::class, 'medicines'])->name('medicines');
    Route::get('/momentum-macd', [PharmacyController::class, 'macd'])->name('macd');
    Route::get('/audit-stok', [PharmacyController::class, 'audit'])->name('audit');
    Route::get('/cetak-lembar-kerja', [PharmacyController::class, 'printLplpo'])->name('print');
});


// =========================================================================
// 3. APLIKASI KHUSUS KEPALA PUSKESMAS & MANAJEMEN (dr. Bambang Utoyo)
// PROTEKSI KETAT: Memerlukan login password role.executive
// =========================================================================
Route::prefix('kepala')->name('executive.')->middleware('role.executive')->group(function () {
    Route::get('/', [ExecutiveController::class, 'index'])->name('dashboard');
    
    // Disposisi & Pengesahan Pengadaan P5
    Route::get('/disposisi-pengadaan', [ExecutiveController::class, 'disposition'])->name('disposition');
    Route::post('/setujui-pengadaan', [ExecutiveController::class, 'approveProcurement'])->name('approve');

    // Analisis & Evaluasi Eksekutif
    Route::get('/morbiditas-simpus', [ExecutiveController::class, 'diseases'])->name('diseases');
    Route::get('/evaluasi-kerugian-ed', [ExecutiveController::class, 'financialLoss'])->name('financial');
    Route::get('/standar-terapi', [ExecutiveController::class, 'correlations'])->name('correlations');
    Route::get('/cetak-lplpo-resmi', [ExecutiveController::class, 'printApprovedLplpo'])->name('print');
    Route::get('/profil-puskesmas', [ExecutiveController::class, 'profile'])->name('profile');
});


// =========================================================================
// 4. ALIAS / REDIREKSI UNTUK KOMPATIBILITAS ROUTE LAMA
// =========================================================================
Route::get('/dashboard-farmasi', fn() => redirect()->route('pharmacy.dashboard'))->name('dashboard.pharmacy');
Route::get('/dashboard-kepala', fn() => redirect()->route('executive.dashboard'))->name('dashboard.executive');
Route::get('/entri-data', fn() => redirect()->route('pharmacy.entry'))->name('data.entry');
Route::get('/rekomendasi-pengadaan', fn() => redirect()->route('pharmacy.procurement'))->name('procurement');
Route::get('/obat', fn() => redirect()->route('pharmacy.medicines'))->name('medicines');
Route::get('/prediksi-penyakit', fn() => redirect()->route('executive.diseases'))->name('diseases');
Route::get('/momentum-macd', fn() => redirect()->route('pharmacy.macd'))->name('macd');
Route::get('/korelasi-morbiditas', fn() => redirect()->route('executive.correlations'))->name('correlation');
Route::get('/audit-stok', fn() => redirect()->route('pharmacy.audit'))->name('audit.stock');
Route::get('/cetak-lplpo', fn() => redirect()->route('pharmacy.print'))->name('lplpo.print');
Route::get('/input-lplpo', fn() => redirect()->route('pharmacy.entry'))->name('lplpo.input');
Route::get('/tentang', fn() => redirect()->route('executive.profile'))->name('about');
Route::get('/ganti-akun/farmasi', fn() => redirect()->route('logout'));
Route::get('/ganti-akun/kepala', fn() => redirect()->route('logout'));
