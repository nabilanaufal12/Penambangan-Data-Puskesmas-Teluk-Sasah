@extends('layouts.app')

@section('title', 'Prediksi 4 Morbiditas Penyakit')
@section('page_title', 'Peramalan Morbiditas Penyakit')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-rose-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8">
        <span class="px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 text-xs font-bold border border-rose-500/30 mb-2 inline-block">
            Saran 3 Dosen Pembimbing (Pak Tekad)
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Peramalan Morbiditas 4 Penyakit Terbanyak</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Analisis deret waktu 45 bulan rekam medis SIMPUS dan proyeksi kebutuhan pelayanan periode 2026-P5 untuk ISPA, Hipertensi, Dispepsia, dan Diabetes Melitus.
        </p>
    </div>

    <!-- 4 Projections Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl p-6 border-l-4 border-l-rose-500 border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400">J00 &bull; ISPA</span>
            <h3 class="text-lg font-extrabold text-navy-950 mt-1">Acute Nasopharyngitis</h3>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-2xl font-extrabold text-rose-600">428,5</span>
                    <span class="text-xs font-semibold text-slate-500">Pasien</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700">WMA-3 (45.8%)</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">Obat terkait: Parasetamol, Amoksisilin, Ambroksol</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border-l-4 border-l-blue-500 border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400">I10 &bull; Hipertensi</span>
            <h3 class="text-lg font-extrabold text-navy-950 mt-1">Hipertensi Primer</h3>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-2xl font-extrabold text-blue-600">383,3</span>
                    <span class="text-xs font-semibold text-slate-500">Pasien</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">SMA-3 (36.0%)</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">Obat terkait: Amlodipin 5/10 mg, Kaptopril</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border-l-4 border-l-emerald-500 border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400">K30 &bull; Lambung</span>
            <h3 class="text-lg font-extrabold text-navy-950 mt-1">Dispepsia (Maag)</h3>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-2xl font-extrabold text-emerald-600">165,7</span>
                    <span class="text-xs font-semibold text-slate-500">Pasien</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">SMA-3 (36.9%)</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">Obat terkait: Antasida DOEN, Ranitidine</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border-l-4 border-l-amber-500 border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400">E11 &bull; Diabetes</span>
            <h3 class="text-lg font-extrabold text-navy-950 mt-1">Diabetes Melitus Tipe 2</h3>
            <div class="mt-4 flex items-baseline justify-between">
                <div>
                    <span class="text-2xl font-extrabold text-amber-600">87,5</span>
                    <span class="text-xs font-semibold text-slate-500">Pasien</span>
                </div>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700">WMA-3 (40.3%)</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">Obat terkait: Metformin 500 mg, Glibenklamid</p>
        </div>

    </div>

    <!-- Chart Visualization Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base">Tren Deret Waktu Morbiditas 4 Penyakit Terbanyak</h3>
                <p class="text-xs text-slate-500">Rekapitulasi 45 bulan pengamatan di poli rawat jalan UPTD Puskesmas Teluk Sasah</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                Interactive Chart.js
            </span>
        </div>
        <div class="h-80 sm:h-96">
            <canvas id="diseaseChart"></canvas>
        </div>
    </div>

    <!-- Methodology Alert Box -->
    <div class="bg-sky-50 border border-sky-200 rounded-2xl p-5 text-sky-950 text-xs flex items-start space-x-3.5">
        <i class="fa-solid fa-circle-info text-medical-600 text-lg mt-0.5"></i>
        <div>
            <h4 class="font-bold text-sm">Transparansi Metodologi: Sifat Data Tersensor Peringkat 10 (*Top-Censored Data*)</h4>
            <p class="mt-1 text-slate-600 leading-relaxed">
                Rekapitulasi bulanan SIMPUS hanya melaporkan 10 penyakit dengan kunjungan tertinggi per bulan. Tiga penyakit utama (J00 ISPA, I10 Hipertensi, K30 Dispepsia) hadir tanpa putus di <strong>seluruh 45 dari 45 bulan pengamatan (100%)</strong>. Penyakit E11 (Diabetes Melitus) tercatat di <strong>43 dari 45 bulan</strong> karena pada September 2024 dan Januari 2025 kunjungannya berada di peringkat 11–12 sehingga tidak masuk rekap 10 besar. Ini adalah fenomena penyensoran atas (*top-censoring*), bukan ketiadaan pasien di lapangan.
            </p>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('diseaseChart').getContext('2d');
        
        // Prepare chart labels and datasets from PHP data
        @php
            $ispaRecords = $diseaseSeries['J00'] ?? collect();
            $labels = $ispaRecords->map(fn($r) => $r->periode_bimonthly_id . ' (' . substr($r->bulan, 0, 3) . ')')->values()->all();
            $ispaData = $diseaseSeries['J00']->pluck('total_pasien')->values()->all();
            $htData = $diseaseSeries['I10']->pluck('total_pasien')->values()->all();
            $k30Data = $diseaseSeries['K30']->pluck('total_pasien')->values()->all();
            $e11Data = $diseaseSeries['E11']->pluck('total_pasien')->values()->all();
        @endphp

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'J00: ISPA (Acute Nasopharyngitis)',
                        data: {!! json_encode($ispaData) !!},
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.08)',
                        tension: 0.3,
                        pointRadius: 2.5,
                        fill: true,
                    },
                    {
                        label: 'I10: Hipertensi Primer',
                        data: {!! json_encode($htData) !!},
                        borderColor: '#3B82F6',
                        backgroundColor: 'transparent',
                        tension: 0.3,
                        pointRadius: 2.5,
                    },
                    {
                        label: 'K30: Dispepsia (Maag)',
                        data: {!! json_encode($k30Data) !!},
                        borderColor: '#10B981',
                        backgroundColor: 'transparent',
                        tension: 0.3,
                        pointRadius: 2.5,
                    },
                    {
                        label: 'E11: Diabetes Melitus Tipe 2',
                        data: {!! json_encode($e11Data) !!},
                        borderColor: '#F59E0B',
                        backgroundColor: 'transparent',
                        tension: 0.3,
                        pointRadius: 2.5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Kunjungan Pasien', font: { family: 'Plus Jakarta Sans', size: 11 } },
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, maxRotation: 45 }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
