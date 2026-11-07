<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/obat', [DashboardController::class, 'medicines'])->name('medicines');
Route::get('/rekomendasi-pengadaan', [DashboardController::class, 'procurement'])->name('procurement');
Route::get('/prediksi-penyakit', [DashboardController::class, 'diseases'])->name('diseases');
Route::get('/momentum-macd', [DashboardController::class, 'macd'])->name('macd');
Route::get('/korelasi-morbiditas', [DashboardController::class, 'correlation'])->name('correlation');
Route::get('/benchmarking-ml', [DashboardController::class, 'mlBenchmark'])->name('ml.benchmark');
Route::get('/audit-stok', [DashboardController::class, 'stockAudit'])->name('audit.stock');
Route::get('/tentang', [DashboardController::class, 'about'])->name('about');
Route::get('/cetak-lplpo', [DashboardController::class, 'printLplpo'])->name('lplpo.print');
Route::get('/input-lplpo', [DashboardController::class, 'inputLplpo'])->name('lplpo.input');
Route::post('/input-lplpo', [DashboardController::class, 'storeLplpo'])->name('lplpo.store');
