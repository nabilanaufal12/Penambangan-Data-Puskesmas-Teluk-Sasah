@extends('layouts.lab')

@section('title', 'Korelasi ICD-10 & Evaluasi Model')

@section('content')

{{-- ======================================================================
     1. SUB-MODUL: RINGKASAN AKURASI BSA
     ====================================================================== --}}
<div id="ringkasan-akurasi" class="mb-6 anim-fade">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-matcha-900 flex items-center gap-2.5">
                <i class="fa-solid fa-microscope text-matcha-700"></i>
                Korelasi Klinis ICD-10 &amp; Evaluasi Model
            </h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">
                Fase 5: Evaluation &bull; Korelasi Pearson (r) &amp; Spearman (&rho;) &bull; Bounded Symmetric Accuracy &bull; Sebaran Residual (&epsilon;_t)
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold" style="background:#CFE1B9; color:#1A3B22;">
            <i class="fa-solid fa-microscope"></i> Evaluation Phase
        </span>
    </div>
</div>

{{-- Kartu Tier Distribusi Akurasi --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 mb-6 anim-fade">
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Akurasi Sangat Baik (&ge;90%)</div>
        <div class="text-2xl font-black text-matcha-900 tnum">{{ number_format($accuracyDist->tier_excellent ?? 0) }}</div>
        <div class="text-xs text-green-700 font-semibold mt-0.5">Sediaan Siap Produksi</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#5C6A44;">
        <div class="text-xs text-gray-500 mb-1">Akurasi Baik (70–90%)</div>
        <div class="text-2xl font-black tnum" style="color:#5C6A44;">{{ number_format($accuracyDist->tier_good ?? 0) }}</div>
        <div class="text-xs text-matcha-700 font-semibold mt-0.5">Memenuhi Standar Akurasi</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#B45309;">
        <div class="text-xs text-gray-500 mb-1">Akurasi Cukup (50–70%)</div>
        <div class="text-2xl font-black tnum text-amber-800">{{ number_format($accuracyDist->tier_fair ?? 0) }}</div>
        <div class="text-xs text-amber-700 font-semibold mt-0.5">Perlu Pengawasan Stok</div>
    </div>
    <div class="lab-card p-4 border-l-4 border-l-red-500">
        <div class="text-xs text-gray-500 mb-1">Akurasi Rendah (&lt;50%)</div>
        <div class="text-2xl font-black tnum text-red-700">{{ number_format($accuracyDist->tier_poor ?? 0) }}</div>
        <div class="text-xs text-red-600 font-semibold mt-0.5">Variansi Pemakaian Ekstrem</div>
    </div>
</div>

{{-- ======================================================================
     2. SUB-MODUL: HEATMAP KORELASI ICD-10 & HISTOGRAM SEBARAN AKURASI
     ====================================================================== --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">

    {{-- Heatmap Korelasi ICD-10 --}}
    <div id="heatmap-korelasi" class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
            <div>
                <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                    <i class="fa-solid fa-table-cells text-matcha-600"></i>
                    Matriks Korelasi Klinis ICD-10 SIMPUS &harr; Sediaan Obat
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Koefisien Linier Pearson (r) &amp; Non-Parametrik Spearman (&rho;)</p>
            </div>
            <span class="text-xs text-matcha-800 font-bold">10 Pasangan Klinis</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr style="background:#1A3B22; color:white;">
                        <th class="px-2.5 py-2 text-left rounded-tl">Diagnosis (ICD-10)</th>
                        <th class="px-2.5 py-2 text-left">Obat Standar</th>
                        <th class="px-2.5 py-2 text-center">Pearson r</th>
                        <th class="px-2.5 py-2 text-center">Spearman &rho;</th>
                        <th class="px-2.5 py-2 text-right rounded-tr">Dosis/Kasus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($correlations as $c)
                    @php
                        $r = abs($c->pearson_r);
                        $rColor = $r >= 0.6 ? '#1A3B22' : ($r >= 0.3 ? '#5C6A44' : '#64748B');
                        $rBg    = $r >= 0.6 ? '#D1FAE5' : ($r >= 0.3 ? '#E7F5DC' : '#F1F5F9');
                        $pct    = min(100, round($r * 100));
                    @endphp
                    <tr class="{{ $loop->even ? 'bg-matcha-50/50' : 'bg-white' }} border-b border-matcha-100/30 hover:bg-matcha-100/70 transition-colors">
                        <td class="px-2.5 py-2">
                            <span class="font-bold text-matcha-900">{{ $c->kode_icd }}</span>
                            <div class="text-[11px] text-gray-500 truncate max-w-[130px]" title="{{ $c->nama_penyakit }}">
                                {{ $c->nama_penyakit }}
                            </div>
                        </td>
                        <td class="px-2.5 py-2">
                            <div class="font-medium text-gray-800 truncate max-w-[120px]" title="{{ $c->nama_obat_standar }}">
                                {{ $c->nama_obat_standar }}
                            </div>
                        </td>
                        <td class="px-2.5 py-2 text-center">
                            <div class="inline-flex flex-col items-center gap-1">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold tnum" style="background:{{ $rBg }}; color:{{ $rColor }};">
                                    {{ number_format($c->pearson_r, 4) }}
                                </span>
                                <div class="w-14 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $rColor }};"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-2.5 py-2 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-sage text-matcha-900">
                                {{ number_format($c->spearman_rho, 4) }}
                            </span>
                        </td>
                        <td class="px-2.5 py-2 text-right font-mono tnum font-bold text-matcha-900">
                            {{ number_format($c->rasio_dosis_per_pasien, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 formula-block text-xs leading-relaxed">
            <strong>Interpretasi Ilmiah:</strong><br>
            Pasangan J00 (ISPA) &harr; Parasetamol (r=0,5851) dan E11 (DM Tipe 2) &harr; Metformin (r=0,5942, &rho;=0,7753) membuktikan secara empiris bahwa tren pemakaian obat berbanding lurus dengan beban morbiditas rawat jalan SIMPUS.
        </div>
    </div>

    {{-- Histogram Sebaran Akurasi --}}
    <div id="histogram-akurasi" class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
            <div>
                <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-matcha-600"></i>
                    Histogram Sebaran Akurasi Simetris (150 Sediaan Obat)
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Distribusi Frekuensi Bounded Symmetric Accuracy (BSA)</p>
            </div>
            <span class="text-xs text-matcha-800 font-bold">10 Interval Kelas</span>
        </div>

        <div class="h-56 relative">
            <canvas id="accuracyHistChart"></canvas>
        </div>

        <div class="mt-4 grid grid-cols-3 gap-2.5 text-center text-xs">
            <div class="p-2.5 rounded-xl bg-matcha-100 border border-matcha-200">
                <div class="text-[10px] text-matcha-700 font-semibold mb-0.5">Rata-rata Akurasi</div>
                <div class="text-base font-black tnum text-matcha-900">{{ number_format($accuracyDist->avg_acc ?? 0, 1) }}%</div>
            </div>
            <div class="p-2.5 rounded-xl bg-sage border border-matcha-100">
                <div class="text-[10px] text-gray-500 mb-0.5">Akurasi Minimum</div>
                <div class="text-base font-black tnum text-amber-800">{{ number_format($accuracyDist->min_acc ?? 0, 1) }}%</div>
            </div>
            <div class="p-2.5 rounded-xl bg-matcha-700 text-white">
                <div class="text-[10px] text-matcha-200 font-semibold mb-0.5">Akurasi Maksimum</div>
                <div class="text-base font-black tnum">{{ number_format($accuracyDist->max_acc ?? 0, 1) }}%</div>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     3. SUB-MODUL: FORMULASI METRIK EVALUASI
     ====================================================================== --}}
<div id="formulasi-evaluasi" class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
        <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
            <i class="fa-solid fa-square-root-variable text-matcha-600"></i>
            Formulasi Matematis Dekomposisi Galat &amp; Validasi Metodologi
        </h2>
        <span class="text-xs text-matcha-700 font-semibold">6 Matriks Komputasi</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-1.5 bg-matcha-900 text-white font-bold text-xs">
                1. Bounded Symmetric Accuracy (BSA)
            </div>
            <div class="p-3 formula-block rounded-none border-0 text-xs leading-relaxed">
                BSA_t = max(0, 100 - (|y_t - &ycirc;_t| / max(y_t, &ycirc;_t, 1)) &times; 100)<br><br>
                Mengeliminasi pembagian nol pada pemakaian 0 dan mengunci akurasi pada skala 0–100%.
            </div>
        </div>

        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-1.5 bg-matcha-800 text-white font-bold text-xs">
                2. Weighted Absolute Percentage Error (WAPE)
            </div>
            <div class="p-3 formula-block rounded-none border-0 text-xs leading-relaxed">
                WAPE = (&Sigma; |y_t - &ycirc;_t|) / (&Sigma; y_t)<br><br>
                Metrik kebal pembagian nol untuk agregasi keseluruhan 150 sediaan obat.
            </div>
        </div>

        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-1.5 bg-matcha-700 text-white font-bold text-xs">
                3. Root Mean Squared Error (RMSE)
            </div>
            <div class="p-3 formula-block rounded-none border-0 text-xs leading-relaxed">
                RMSE = &radic;[ (1/n) &times; &Sigma; (y_t - &ycirc;_t)&sup2; ]<br><br>
                Memberikan penalti kuadratik yang lebih keras terhadap lonjakan deviasi ekstrem.
            </div>
        </div>

        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-1.5 bg-matcha-900 text-white font-bold text-xs">
                4. Korelasi Linier Pearson (r)
            </div>
            <div class="p-3 formula-block rounded-none border-0 text-xs leading-relaxed">
                r = &Sigma;(X - X̄)(Y - Ȳ) / &radic;[ &Sigma;(X - X̄)&sup2; &Sigma;(Y - Ȳ)&sup2; ]<br><br>
                Mengukur kekuatan dan arah hubungan linier kunjungan SIMPUS vs logistik obat.
            </div>
        </div>

        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-1.5 bg-matcha-800 text-white font-bold text-xs">
                5. Korelasi Spearman Rank (&rho;)
            </div>
            <div class="p-3 formula-block rounded-none border-0 text-xs leading-relaxed">
                &rho; = 1 - [ (6 &times; &Sigma; d_i&sup2;) / (n(n&sup2; - 1)) ]<br><br>
                Korelasi non-parametrik yang tangguh terhadap pencilan data kunjungan pasien.
            </div>
        </div>

        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-1.5 bg-matcha-700 text-white font-bold text-xs">
                6. Sebaran Residual (&epsilon;_t)
            </div>
            <div class="p-3 formula-block rounded-none border-0 text-xs leading-relaxed">
                &epsilon;_t = y_t - &ycirc;_t<br><br>
                Residual terpusat di sekitar nol membuktikan model WMA-3 tidak memiliki bias destruktif.
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     4. SUB-MODUL: DIAGNOSTIK SEBARAN RESIDUAL
     ====================================================================== --}}
<div id="diagnostik-residual" class="lab-card p-5 mb-6 anim-fade" style="border-top:4px solid #1A3B22;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-2 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-water-ladder text-matcha-600"></i>
                Diagnostik Sebaran Residual (&epsilon;_t = y_t &minus; &ycirc;_t) Sediaan Aktif
            </h2>
            <p id="residual-drug-title" class="text-xs text-gray-500 mt-0.5">
                Sediaan: Parasetamol tablet 500 mg &bull; Model WMA-3
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span id="residual-acc-pill" class="px-2.5 py-1 rounded-full text-xs font-bold bg-matcha-100 text-matcha-900">
                Akurasi BSA: 95,0%
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 h-56 relative">
            <canvas id="residualChart"></canvas>
        </div>
        <div class="flex flex-col justify-between p-4 rounded-xl bg-sage border border-matcha-100 text-xs">
            <div>
                <div class="font-bold text-matcha-900 mb-2">Evaluasi Kualitas Model:</div>
                <ul class="space-y-1.5 text-gray-700 leading-snug">
                    <li class="flex items-start gap-1.5">
                        <i class="fa-solid fa-circle-check text-green-700 mt-0.5 text-[10px]"></i>
                        <span>Residual berosilasi di sekitar sumbu nol (&plusmn;).</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <i class="fa-solid fa-circle-check text-green-700 mt-0.5 text-[10px]"></i>
                        <span>Tidak ditemukan pola autocorrelation musiman tajam.</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <i class="fa-solid fa-shield-halved text-amber-700 mt-0.5 text-[10px]"></i>
                        <span>Bias positif alami (+4,3%) bertindak sebagai buffer penangkal stockout.</span>
                    </li>
                </ul>
            </div>
            <div id="residual-interp-box" class="p-2.5 rounded-lg bg-matcha-100 text-matcha-900 font-semibold mt-3 text-[11px]">
                Status Model: Sangat Stabil &amp; Layak Produksi
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     5. SUB-MODUL: LEADERBOARD BENCHMARK MODEL
     ====================================================================== --}}
<div id="leaderboard-benchmark" class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-ranking-star text-amber-500"></i>
                Tabel Leaderboard Evaluasi Model — 10-Fold Walk-Forward Cross Validation
            </h2>
            <p class="text-gray-500 text-xs mt-0.5">Komparasi Machine Learning vs. Baseline Statistik</p>
        </div>
        <span class="text-xs text-matcha-800 font-bold">WAPE &amp; Bias Audit</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#1A3B22; color:white;">
                    <th class="px-3 py-2.5 text-left rounded-tl-lg">Peringkat</th>
                    <th class="px-3 py-2.5 text-left">Nama Algoritma / Model</th>
                    <th class="px-3 py-2.5 text-center">Kategori</th>
                    <th class="px-3 py-2.5 text-right">WAPE (%)</th>
                    <th class="px-3 py-2.5 text-center">Bias (%)</th>
                    <th class="px-3 py-2.5 text-right">Stabilitas SD</th>
                    <th class="px-3 py-2.5 text-left rounded-tr-lg">Interpretasi Praktik Faskes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($benchmarks as $i => $b)
                @php
                    $isTop = $i === 0;
                    $isWma = str_contains($b->model_name, 'WMA');
                @endphp
                <tr class="{{ $isTop ? 'bg-matcha-100 font-bold' : ($isWma ? 'bg-amber-50/80 font-bold border-l-4 border-l-amber-600' : ($loop->even ? 'bg-matcha-50/40' : 'bg-white')) }} border-b border-matcha-100/30 hover:bg-matcha-100/70 transition-colors">
                    <td class="px-3 py-2">
                        @if($isTop)
                            <span class="inline-flex items-center gap-1 text-amber-600 font-black"><i class="fa-solid fa-trophy"></i> #1</span>
                        @else
                            <span class="text-gray-400 font-bold">#{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 {{ $isTop ? 'text-matcha-950 font-black' : 'text-gray-800' }}">
                        {{ $b->model_name }}
                        @if($isWma)
                            <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] bg-matcha-700 text-white font-bold">Model Utama PRABOTIK</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-center">
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-matcha-100 text-matcha-900">
                            {{ $b->kategori }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum font-black {{ $b->wape <= 0.6 ? 'text-green-800' : 'text-matcha-900' }}">
                        {{ number_format($b->wape * 100, 1) }}%
                    </td>
                    <td class="px-3 py-2 text-center font-mono tnum">
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ str_contains($b->bias_percentage, '+') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $b->bias_percentage }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum text-gray-600">{{ number_format($b->stabilitas_sd, 3) }}</td>
                    <td class="px-3 py-2 text-gray-700 text-[11px]">{{ $b->interpretasi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ======================================================================
     6. SUB-MODUL: PREDIKSI KEBUTUHAN P5
     ====================================================================== --}}
<div id="prediksi-kebutuhan" class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-pills text-matcha-600"></i>
                Proyeksi Kasus &amp; Kebutuhan Obat 2026-P5 Berbasis Korelasi Klinis
            </h2>
            <p class="text-gray-500 text-xs mt-0.5">Integrasi Morbiditas SIMPUS terhadap Rencana Kebutuhan Logistik Farmasi</p>
        </div>
        <span class="text-xs text-matcha-800 font-bold">Sinkronisasi P5</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#5C6A44; color:white;">
                    <th class="px-3 py-2.5 text-left rounded-tl-lg">Kode ICD</th>
                    <th class="px-3 py-2.5 text-left">Nama Diagnosis Penyakit</th>
                    <th class="px-3 py-2.5 text-left">Sediaan Obat Standar</th>
                    <th class="px-3 py-2.5 text-right">Pearson r</th>
                    <th class="px-3 py-2.5 text-right">Prediksi Kasus P5</th>
                    <th class="px-3 py-2.5 text-right rounded-tr-lg">Prediksi Kebutuhan Obat P5</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topDiseasesPred as $d)
                <tr class="{{ $loop->even ? 'bg-matcha-50/50' : 'bg-white' }} border-b border-matcha-100/30 hover:bg-matcha-100/70 transition-colors">
                    <td class="px-3 py-2 font-mono font-bold" style="color:#1A3B22;">{{ $d->kode_icd }}</td>
                    <td class="px-3 py-2 font-bold text-gray-800">{{ $d->nama_penyakit }}</td>
                    <td class="px-3 py-2 text-gray-700">{{ $d->nama_obat_standar }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum font-bold text-matcha-800">
                        {{ number_format($d->pearson_r, 4) }}
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum font-bold text-gray-800">
                        {{ number_format($d->prediksi_kasus_P5) }} pasien
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum font-black text-matcha-900">
                        {{ number_format($d->prediksi_kebutuhan_obat_P5) }} tablet/botol
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ======================================================================
     7. SUB-MODUL: MODE SIDANG (RINGKASAN EKSEKUTIF UNTUK UJIAN)
     ====================================================================== --}}
<div id="mode-sidang" class="lab-card p-6 mb-6 anim-fade" style="border:3px solid #1A3B22; background: linear-gradient(170deg, #FFFFFF 0%, #F4F6F2 100%);">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-5 border-b-2 border-matcha-800/20 pb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-md" style="background:#1A3B22;">
                <i class="fa-solid fa-graduation-cap text-matcha-200 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg sm:text-xl font-black text-matcha-950">
                    Mode Sidang Tugas Besar — Ringkasan Ketertelusuran Akademik
                </h2>
                <div class="text-xs text-matcha-800 font-semibold mt-0.5">
                    Kelompok 23 &bull; INF11121 &bull; UMRAH &bull; NIM: 2401020069
                </div>
            </div>
        </div>

        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300">
            1-Click Defense Summary
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5 text-xs text-gray-800">
        <div class="p-4 rounded-xl bg-white border border-matcha-200 shadow-sm space-y-2">
            <div class="font-extrabold text-sm text-matcha-900 flex items-center gap-2 mb-2">
                <i class="fa-solid fa-check-double text-green-700"></i>
                Pencapaian 3 Paradoks Riil Mitra (Puskesmas Teluk Sasah)
            </div>
            <p><strong>1. SPM Kemenkes Tercapai 91,3%:</strong> Melampaui batas minimum SPM &ge;80% melalui keterjaminan stok 150 sediaan obat esensial.</p>
            <p><strong>2. Eliminasi Kerugian Overstock:</strong> Mencegah pengulangan kerugian obat kadaluarsa sebesar Rp 43.348.626,- (138 item Berita Acara) berkat rekomendasi pengadaan saintifik.</p>
            <p><strong>3. Anti-Spiraling Stockout:</strong> Koreksi algoritma Censored Demand Imputation mencegah anjloknya proyeksi Parasetamol 500 mg akibat stok kosong.</p>
        </div>

        <div class="p-4 rounded-xl bg-white border border-matcha-200 shadow-sm space-y-2">
            <div class="font-extrabold text-sm text-matcha-900 flex items-center gap-2 mb-2">
                <i class="fa-solid fa-microscope text-matcha-700"></i>
                Validitas Ilmiah Metodologi CRISP-DM
            </div>
            <p><strong>WMA-3 Terpilih Sebagai Model Utama:</strong> Bobot linier [3, 2, 1] memberikan penalti waktu logis, menghasilkan WAPE stabil 59,5% dengan bias positif aman (+4,3%) sebagai buffer logistik.</p>
            <p><strong>Korelasi Klinis Terbukti:</strong> Analisis bivariat membuktikan korelasi kuat antara penyakit musiman (ISPA, Hipertensi, DM) dengan konsumsi obat standar.</p>
            <p><strong>Arsitektur Tri-Portal:</strong> Pemisahan ketat antara Operasional (/farmasi), Otorisasi Anggaran (/kepala), dan Validasi Data (/lab).</p>
        </div>
    </div>

    {{-- Tombol Alih Cepat Halaman Sidang --}}
    <div class="flex flex-wrap items-center gap-2.5 pt-3 border-t border-matcha-200">
        <a href="{{ route('lab.eda') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white" style="background:#1A3B22;">
            <i class="fa-solid fa-diagram-project"></i> 1. Pipeline &amp; EDA
        </a>
        <a href="{{ route('lab.preprocess') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white" style="background:#5C6A44;">
            <i class="fa-solid fa-filter"></i> 2. Pra-Proses
        </a>
        <a href="{{ route('lab.modeling') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white" style="background:#728156;">
            <i class="fa-solid fa-chart-line"></i> 3. Arena Model
        </a>
        <a href="{{ route('pharmacy.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold border border-matcha-700 text-matcha-900 hover:bg-matcha-100 transition-all">
            <i class="fa-solid fa-pills"></i> Hasil Deployment (/farmasi)
        </a>
        <a href="{{ route('executive.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold border border-matcha-700 text-matcha-900 hover:bg-matcha-100 transition-all">
            <i class="fa-solid fa-user-tie"></i> Otorisasi Pimpinan (/kepala)
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- 1. Histogram Akurasi ----
(function () {
    const ctx = document.getElementById('accuracyHistChart');
    if (!ctx) return;
    const bins  = @json($accuracyBins);
    const order = ['0-10%', '10-20%', '20-30%', '30-40%', '40-50%', '50-60%', '60-70%', '70-80%', '80-90%', '90-100%'];
    const map   = {};
    bins.forEach(b => map[b.bin_label] = b.count);
    const counts = order.map(k => map[k] || 0);
    const colors = order.map((_, i) => i >= 8 ? '#1A3B22' : (i >= 6 ? '#5C6A44' : (i >= 4 ? '#CFE1B9' : '#FCA5A5')));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: order,
            datasets: [{
                label: 'Jumlah Sediaan Obat',
                data: counts,
                backgroundColor: colors,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: c => ` ${c.parsed.y} obat` }
                }
            },
            scales: {
                x: {
                    ticks: { font: { size: 9 }, maxRotation: 35 },
                    grid: { display: false }
                },
                y: {
                    grid: { color: '#E7F5DC' },
                    ticks: { font: { size: 9 }, stepSize: 1 }
                }
            }
        }
    });
})();

// ---- 2. Residual Chart Live Switcher ----
let residualChartInstance = null;

document.addEventListener('drugLoaded', function (e) {
    const d = e.detail;

    // Judul & Badge
    const titleEl = document.getElementById('residual-drug-title');
    const accPill = document.getElementById('residual-acc-pill');
    const boxEl   = document.getElementById('residual-interp-box');

    if (titleEl) titleEl.textContent = `Sediaan: ${d.nama_obat} (${d.satuan}) &bull; Model ${d.model_terbaik}`;
    if (accPill) accPill.textContent = `Akurasi BSA: ${d.evaluasi_akurasi}% (MAPE: ${d.mape}%)`;

    if (boxEl) {
        if (d.evaluasi_akurasi >= 85) {
            boxEl.className = 'p-2.5 rounded-lg bg-green-100 text-green-900 font-bold mt-3 text-[11px]';
            boxEl.textContent = 'Status Model: Sangat Stabil & Layak Deploy ke Produksi Faskes';
        } else if (d.evaluasi_akurasi >= 65) {
            boxEl.className = 'p-2.5 rounded-lg bg-matcha-100 text-matcha-900 font-bold mt-3 text-[11px]';
            boxEl.textContent = 'Status Model: Baik & Terkendali dengan Pengawasan Stok Berkala';
        } else {
            boxEl.className = 'p-2.5 rounded-lg bg-amber-100 text-amber-900 font-bold mt-3 text-[11px]';
            boxEl.textContent = 'Status Model: Variabilitas Tinggi, Rekomendasi Alokasi Safety Stock Maksimal';
        }
    }

    const ctx = document.getElementById('residualChart');
    if (!ctx) return;

    if (residualChartInstance) residualChartInstance.destroy();

    const residuals = d.residuals || [];
    const rLabels   = residuals.map((_, i) => `P${i - residuals.length + 1}`);

    residualChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: rLabels,
            datasets: [{
                label: 'Residual (\u03B5_t = y_t \u2212 \u0177_t)',
                data: residuals,
                backgroundColor: residuals.map(v => v >= 0 ? 'rgba(26,59,34,0.7)' : 'rgba(220,38,38,0.7)'),
                borderRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { font: { size: 10, family: 'Plus Jakarta Sans' }, boxWidth: 12 } },
                tooltip: {
                    callbacks: { label: c => ` \u03B5 = ${c.parsed.y.toLocaleString('id-ID')} unit` }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 } }
                },
                y: {
                    grid: { color: '#E7F5DC' },
                    ticks: { font: { size: 9 } }
                }
            }
        }
    });
});
</script>
@endpush
