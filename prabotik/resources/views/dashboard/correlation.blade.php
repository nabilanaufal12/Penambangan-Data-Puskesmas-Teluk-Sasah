@extends('layouts.app')

@section('title', 'Korelasi Klinis & Rasio Dosis')
@section('page_title', 'Korelasi Klinis Penyakit & Obat')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8">
        <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold border border-indigo-500/30 mb-2 inline-block">
            Saran 3a Dosen Pembimbing (Pak Tekad)
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Korelasi Penyakit-Obat & Rasio Dosis Klinis</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Pengujian korelasi parametrik Pearson ($r$), non-parametrik Spearman ($\rho$), serta rasio resep empiris per pasien rawat jalan untuk estimasi kebutuhan obat terpadu berbasis diagnosis morbiditas.
        </p>
    </div>

    <!-- Correlation Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-700">Matriks Korelasi Terperinci Penyakit-Obat & Estimasi Berbasis Kasus (2026-P5)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">Kode</th>
                        <th class="px-5 py-3.5">Diagnosis Morbiditas</th>
                        <th class="px-5 py-3.5">Nama Obat Utama</th>
                        <th class="px-4 py-3.5">Indikasi Farmakologi</th>
                        <th class="px-3 py-3.5 text-right font-mono">Pearson $r$</th>
                        <th class="px-3 py-3.5 text-right font-mono">Spearman $\rho$</th>
                        <th class="px-4 py-3.5 text-right font-extrabold text-slate-900">Rasio Dosis (Butir/Pasien)</th>
                        <th class="px-4 py-3.5 text-right text-rose-600">Prediksi Kasus P5</th>
                        <th class="px-5 py-3.5 text-right font-extrabold text-emerald-700 bg-emerald-50/40">Estimasi Kebutuhan P5</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($correlations as $c)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3.5 font-bold text-slate-500">{{ $c->kode_icd }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $c->nama_penyakit }}</td>
                        <td class="px-5 py-3.5 font-bold text-medical-800">{{ $c->nama_obat_standar }}</td>
                        <td class="px-4 py-3.5 text-slate-600">{{ $c->indikasi_klinis }}</td>
                        <td class="px-3 py-3.5 text-right font-mono font-bold {{ $c->pearson_r > 0.3 ? 'text-emerald-600' : ($c->pearson_r < 0 ? 'text-rose-600' : 'text-slate-600') }}">
                            {{ $c->pearson_r > 0 ? '+' : '' }}{{ number_format($c->pearson_r, 4, ',', '.') }}
                        </td>
                        <td class="px-3 py-3.5 text-right font-mono text-slate-600">
                            {{ $c->spearman_rho > 0 ? '+' : '' }}{{ number_format($c->spearman_rho, 4, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5 text-right font-extrabold text-slate-900 text-sm">
                            {{ number_format($c->rasio_dosis_per_pasien, 2, ',', '.') }} <span class="text-[10px] text-slate-400 font-normal">tab</span>
                        </td>
                        <td class="px-4 py-3.5 text-right font-bold text-rose-600">
                            {{ number_format($c->prediksi_kasus_P5, 0, ',', '.') }} jiwa
                        </td>
                        <td class="px-5 py-3.5 text-right font-extrabold text-emerald-800 bg-emerald-50/40 text-sm">
                            {{ number_format($c->prediksi_kebutuhan_obat_P5, 0, ',', '.') }} butir
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Explanatory Insights Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <h4 class="font-extrabold text-slate-900 text-sm mb-2 flex items-center">
                <i class="fa-solid fa-pills mr-2 text-medical-600"></i>
                Mengapa Rasio Dosis Metformin Mencapai ~40 Butir/Pasien?
            </h4>
            <p class="text-xs text-slate-600 leading-relaxed">
                Rasio resep 39,95 butir per pasien pada Metformin mencerminkan <strong>terapi pemeliharaan rutin pasien kronis (Prolanis)</strong>. Pasien diabetes mengambil persediaan obat harian untuk 1–2 bulan sekaligus (1-2 tablet per hari selama 60 hari = 60-120 tablet). Ini bukan konsumsi episode tunggal akut.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <h4 class="font-extrabold text-slate-900 text-sm mb-2 flex items-center">
                <i class="fa-solid fa-triangle-exclamation mr-2 text-amber-500"></i>
                Penyebab Korelasi Lemah/Negatif pada Amlodipin
            </h4>
            <p class="text-xs text-slate-600 leading-relaxed">
                Nilai Pearson $r = -0.2539$ pada Amlodipin 10 mg bukan karena obat tidak digunakan untuk hipertensi, melainkan akibat <strong>kekosongan stok fisik (stockout) berulang di puskesmas (7 periode stok habis)</strong> sehingga pemakaian tercatat 0 saat kunjungan pasien hipertensi tetap tinggi di poli.
            </p>
        </div>
    </div>

</div>
@endsection
