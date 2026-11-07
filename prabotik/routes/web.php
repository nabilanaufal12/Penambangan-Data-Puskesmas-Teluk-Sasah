<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Dashboard Berdasarkan Peran (Farmasi vs Kepala Puskesmas/Admin)
Route::get('/', [DashboardController::class, 'pharmacyDashboard'])->name('dashboard');
Route::get('/dashboard-farmasi', [DashboardController::class, 'pharmacyDashboard'])->name('dashboard.pharmacy');
Route::get('/dashboard-kepala', [DashboardController::class, 'executiveDashboard'])->name('dashboard.executive');
Route::get('/obat', [DashboardController::class, 'medicines'])->name('medicines');
Route::get('/rekomendasi-pengadaan', [DashboardController::class, 'procurement'])->name('procurement');
Route::get('/prediksi-penyakit', [DashboardController::class, 'diseases'])->name('diseases');
Route::get('/momentum-macd', [DashboardController::class, 'macd'])->name('macd');
Route::get('/korelasi-morbiditas', [DashboardController::class, 'correlation'])->name('correlation');
Route::get('/benchmarking-ml', [DashboardController::class, 'mlBenchmark'])->name('ml.benchmark');
Route::get('/audit-stok', [DashboardController::class, 'stockAudit'])->name('audit.stock');
Route::get('/tentang', [DashboardController::class, 'about'])->name('about');
Route::get('/cetak-lplpo', [DashboardController::class, 'printLplpo'])->name('lplpo.print');
Route::get('/input-lplpo', [DashboardController::class, 'dataEntry'])->name('lplpo.input');
Route::post('/input-lplpo', [DashboardController::class, 'storeLplpo'])->name('lplpo.store');

// Beralih Akun Pengguna (Apt. Siti Rahmawati vs dr. Bambang Utoyo)
Route::get('/ganti-akun/{role}', [DashboardController::class, 'switchUser'])->name('user.switch');

// Otorisasi & Disposisi Pengesahan Pengadaan oleh Kepala Puskesmas
Route::post('/setujui-pengadaan', [DashboardController::class, 'approveProcurement'])->name('procurement.approve');

// Pusat Entri Data Faskes Terpadu (4 Modul: LPLPO, Morbiditas SIMPUS, Berita Acara ED, Registrasi Obat)
Route::get('/entri-data', [DashboardController::class, 'dataEntry'])->name('data.entry');
Route::post('/entri-data/lplpo', [DashboardController::class, 'storeLplpo'])->name('data.entry.lplpo');
Route::post('/entri-data/penyakit', [DashboardController::class, 'storeDisease'])->name('data.entry.disease');
Route::post('/entri-data/kadaluarsa', [DashboardController::class, 'storeExpired'])->name('data.entry.expired');
Route::post('/entri-data/obat', [DashboardController::class, 'storeNewMedicine'])->name('data.entry.medicine');
