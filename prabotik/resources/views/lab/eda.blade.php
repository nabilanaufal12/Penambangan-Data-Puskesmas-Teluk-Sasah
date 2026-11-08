@extends('layouts.lab')

@section('title', 'Pipeline CRISP-DM & EDA')

@section('content')

{{-- ======================================================================
     1. SUB-MODUL: ALUR CRISP-DM 6 FASE INTERAKTIF
     ====================================================================== --}}
<div id="alur-crisp-dm" class="lab-card p-6 mb-6 anim-fade">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-5 border-b border-matcha-100 pb-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-matcha-900 flex items-center gap-2.5">
                <i class="fa-solid fa-diagram-project text-matcha-700"></i>
                Pipeline Metodologi CRISP-DM End-to-End
            </h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">
                Ketertelusuran Komputasi Sains Data: 31.125 Kunjungan SIMPUS &bull; 150 Sediaan LPLPO &bull; 138 Berita Acara ED (Rp 43,35 Juta)
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold" style="background:#CFE1B9; color:#1A3B22;">
                <i class="fa-solid fa-shield-halved text-[10px]"></i> Standar Industri
            </span>
        </div>
    </div>

    {{-- Diagram Alur 6 Fase Interaktif --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
        {{-- Fase 1 --}}
        <div class="rounded-xl p-3.5 flex flex-col justify-between border transition-all hover:shadow-md" style="background:#1A3B22; color:white; border-color:#142416;">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black bg-white/20">1</span>
                    <i class="fa-solid fa-bullseye text-sm text-matcha-200"></i>
                </div>
                <div class="font-bold text-xs uppercase tracking-wider text-matcha-200 mb-1">Fase 1</div>
                <div class="font-bold text-sm leading-tight text-white mb-2">Business Understanding</div>
                <p class="text-[11px] text-matcha-100 leading-snug">
                    Target SPM &ge;80% (Aktual 91,3%), mitigasi ED Rp 43,35 Juta, eliminasi stockout obat vital.
                </p>
            </div>
            <div class="mt-3 pt-2 border-t border-white/10 text-[10px] font-semibold text-matcha-300">
                &bull; Kemenkes &amp; APBD Bintan
            </div>
        </div>

        {{-- Fase 2 --}}
        <div class="rounded-xl p-3.5 flex flex-col justify-between border transition-all hover:shadow-md" style="background:#5C6A44; color:white; border-color:#3E4A2F;">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black bg-white/20">2</span>
                    <i class="fa-solid fa-database text-sm text-matcha-200"></i>
                </div>
                <div class="font-bold text-xs uppercase tracking-wider text-matcha-200 mb-1">Fase 2</div>
                <div class="font-bold text-sm leading-tight text-white mb-2">Data Understanding</div>
                <p class="text-[11px] text-matcha-100 leading-snug">
                    Ingesti 150 obat (22 periode dwibulanan 2023–2026), 31.125 rekam SIMPUS, &amp; 10 ICD-10.
                </p>
            </div>
            <div class="mt-3 pt-2 border-t border-white/10 text-[10px] font-semibold text-matcha-300">
                &bull; Eksplorasi &amp; Profil Statistik
            </div>
        </div>

        {{-- Fase 3 (Linkable) --}}
        <a href="{{ route('lab.preprocess') }}"
           class="group rounded-xl p-3.5 flex flex-col justify-between border transition-all hover:shadow-md hover:scale-[1.02]"
           style="background:#728156; color:white; border-color:#5C6A44;">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black bg-white/20">3</span>
                    <i class="fa-solid fa-filter text-sm text-matcha-200"></i>
                </div>
                <div class="font-bold text-xs uppercase tracking-wider text-matcha-200 mb-1">Fase 3</div>
                <div class="font-bold text-sm leading-tight text-white mb-2">Data Preparation</div>
                <p class="text-[11px] text-matcha-100 leading-snug">
                    Pembersihan artefak, rekonstruksi Censored Demand, &amp; kalkulasi Safety Stock Z&middot;&sigma;&middot;&radic;L.
                </p>
            </div>
            <div class="mt-3 pt-2 border-t border-white/10 flex items-center justify-between text-[11px] font-bold text-matcha-100 group-hover:text-white">
                <span>Buka Pra-Proses</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </div>
        </a>

        {{-- Fase 4 (Linkable) --}}
        <a href="{{ route('lab.modeling') }}"
           class="group rounded-xl p-3.5 flex flex-col justify-between border transition-all hover:shadow-md hover:scale-[1.02]"
           style="background:#5C6A44; color:white; border-color:#3E4A2F;">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black bg-white/20">4</span>
                    <i class="fa-solid fa-chart-line text-sm text-matcha-200"></i>
                </div>
                <div class="font-bold text-xs uppercase tracking-wider text-matcha-200 mb-1">Fase 4</div>
                <div class="font-bold text-sm leading-tight text-white mb-2">Modeling</div>
                <p class="text-[11px] text-matcha-100 leading-snug">
                    WMA-3 (w=[3,2,1]) vs SMA-3 vs SES (&alpha;=0,3) vs EMA, serta indikator Momentum MACD.
                </p>
            </div>
            <div class="mt-3 pt-2 border-t border-white/10 flex items-center justify-between text-[11px] font-bold text-matcha-100 group-hover:text-white">
                <span>Buka Arena Model</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </div>
        </a>

        {{-- Fase 5 (Linkable) --}}
        <a href="{{ route('lab.evaluation') }}"
           class="group rounded-xl p-3.5 flex flex-col justify-between border transition-all hover:shadow-md hover:scale-[1.02]"
           style="background:#1A3B22; color:white; border-color:#142416;">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black bg-white/20">5</span>
                    <i class="fa-solid fa-microscope text-sm text-matcha-200"></i>
                </div>
                <div class="font-bold text-xs uppercase tracking-wider text-matcha-200 mb-1">Fase 5</div>
                <div class="font-bold text-sm leading-tight text-white mb-2">Evaluation</div>
                <p class="text-[11px] text-matcha-100 leading-snug">
                    Korelasi Pearson/Spearman ICD-10, evaluasi galat BSA, WAPE, MAPE, &amp; sebaran residual.
                </p>
            </div>
            <div class="mt-3 pt-2 border-t border-white/10 flex items-center justify-between text-[11px] font-bold text-matcha-100 group-hover:text-white">
                <span>Buka Evaluasi</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </div>
        </a>

        {{-- Fase 6 --}}
        <div class="rounded-xl p-3.5 flex flex-col justify-between border transition-all hover:shadow-md" style="background:#3E4A2F; color:white; border-color:#2A3420;">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black bg-white/20">6</span>
                    <i class="fa-solid fa-rocket text-sm text-matcha-200"></i>
                </div>
                <div class="font-bold text-xs uppercase tracking-wider text-matcha-200 mb-1">Fase 6</div>
                <div class="font-bold text-sm leading-tight text-white mb-2">Deployment</div>
                <p class="text-[11px] text-matcha-100 leading-snug">
                    Persistensi SQLite WAL ACID, usulan pengadaan /farmasi, &amp; disposisi legal ber-NIP /kepala.
                </p>
            </div>
            <div class="mt-3 pt-2 border-t border-white/10 text-[10px] font-semibold text-matcha-300">
                &bull; Tri-Portal PRABOTIK
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     2. SUB-MODUL: KARTU INDIKATOR STATISTIK KPI
     ====================================================================== --}}
<div id="ringkasan-statistik" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3.5 mb-6 anim-fade">
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-1.5" style="background:#E7F5DC;">
            <i class="fa-solid fa-capsules text-xs" style="color:#1A3B22;"></i>
        </div>
        <div class="text-xl font-black text-matcha-900 tnum">{{ number_format($totalMedicines) }}</div>
        <div class="text-[11px] font-bold text-gray-700">Total Sediaan Obat</div>
        <div class="text-[10px] text-gray-500">100% Terpetakan LPLPO</div>
    </div>

    <div class="lab-card p-4 border-l-4" style="border-left-color:#5C6A44;">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-1.5" style="background:#F3F8EE;">
            <i class="fa-solid fa-hospital-user text-xs" style="color:#5C6A44;"></i>
        </div>
        <div class="text-xl font-black tnum" style="color:#5C6A44;">{{ number_format($totalVisits) }}</div>
        <div class="text-[11px] font-bold text-gray-700">Kunjungan SIMPUS</div>
        <div class="text-[10px] text-gray-500">10 Besar Kasus ICD-10</div>
    </div>

    <div class="lab-card p-4 border-l-4" style="border-left-color:#B45309;">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-1.5" style="background:#FEF3C7;">
            <i class="fa-solid fa-triangle-exclamation text-xs text-amber-700"></i>
        </div>
        <div class="text-xl font-black tnum text-amber-800">Rp 43,35 M</div>
        <div class="text-[11px] font-bold text-gray-700">Kerugian Obat ED</div>
        <div class="text-[10px] text-gray-500">{{ $totalEdItems }} Item Berita Acara</div>
    </div>

    <div class="lab-card p-4 border-l-4 border-l-red-500">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-1.5 bg-red-100">
            <i class="fa-solid fa-boxes-packing text-xs text-red-700"></i>
        </div>
        <div class="text-xl font-black tnum text-red-700">{{ number_format($stockout) }}</div>
        <div class="text-[11px] font-bold text-gray-700">Obat Stok Kosong</div>
        <div class="text-[10px] text-gray-500">SOH Gudang = 0</div>
    </div>

    <div class="lab-card p-4 border-l-4" style="border-left-color:#728156;">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-1.5" style="background:#E7F5DC;">
            <i class="fa-solid fa-wand-magic-sparkles text-xs" style="color:#728156;"></i>
        </div>
        <div class="text-xl font-black tnum" style="color:#728156;">{{ number_format($censored) }}</div>
        <div class="text-[11px] font-bold text-gray-700">Censored Demand</div>
        <div class="text-[10px] text-gray-500">Parasetamol 500 mg</div>
    </div>

    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="w-7 h-7 rounded-lg flex items-center justify-center mb-1.5" style="background:#CFE1B9;">
            <i class="fa-solid fa-bullseye text-xs" style="color:#1A3B22;"></i>
        </div>
        <div class="text-xl font-black tnum" style="color:#1A3B22;">{{ number_format($avgAccuracy, 1) }}%</div>
        <div class="text-[11px] font-bold text-gray-700">Rata-rata Akurasi</div>
        <div class="text-[10px] text-gray-500">Bounded Symmetric</div>
    </div>
</div>

{{-- ======================================================================
     3. SUB-MODUL: KARTU INSPEKSI CEPAT SEDIAAN OBAT (LIVE SWITCHER)
     ====================================================================== --}}
<div id="inspeksi-obat" class="lab-card p-5 mb-6 anim-fade" style="border-top:4px solid #1A3B22;">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4 pb-3 border-b border-matcha-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider text-white" style="background:#1A3B22;">
                    Inspeksi Cepat Sediaan
                </span>
                <span id="card-lens-badge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-matcha-100 text-matcha-800">
                    22 Periode Dwibulanan
                </span>
            </div>
            <h2 id="card-drug-name" class="text-lg sm:text-xl font-extrabold text-matcha-900 mt-1">
                Parasetamol tablet 500 mg
            </h2>
            <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5">
                <span id="card-drug-satuan">Satuan: tablet</span>
                &bull;
                <span id="card-drug-prioritas" class="font-semibold text-matcha-800">Prioritas 1 (Vital / Fast-Moving)</span>
                &bull;
                <span id="card-drug-momentum" class="font-bold text-green-700">📈 Pemulihan Tren</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('lab.modeling') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white transition-all hover:opacity-90"
               style="background:#5C6A44;">
                <i class="fa-solid fa-chart-line"></i> Buka di Arena Model
            </a>
            <a href="{{ route('lab.preprocess') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold border border-matcha-200 text-gray-700 hover:bg-sage transition-all">
                <i class="fa-solid fa-filter"></i> Buka di Pra-Proses
            </a>
        </div>
    </div>

    {{-- Grid 4 Metrik Kunci --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="p-3 rounded-xl bg-sage border border-matcha-100 text-center">
            <div class="text-[11px] text-gray-500 mb-0.5">Model Terbaik Terpilih</div>
            <div id="card-best-model" class="text-base font-extrabold text-matcha-900">SMA (k=2)</div>
            <div id="card-model-acc" class="text-xs font-bold text-matcha-700 mt-0.5">Akurasi 95,0% (MAPE 36,4%)</div>
        </div>

        <div class="p-3 rounded-xl bg-sage border border-matcha-100 text-center">
            <div class="text-[11px] text-gray-500 mb-0.5">Prediksi Kebutuhan P5</div>
            <div id="card-pred-p5" class="text-base font-black tnum text-matcha-900">6.147</div>
            <div class="text-[10px] text-gray-500 mt-0.5">Baseline Konsumsi</div>
        </div>

        <div class="p-3 rounded-xl bg-sage border border-matcha-100 text-center">
            <div class="text-[11px] text-gray-500 mb-0.5">Safety Stock (Z&middot;&sigma;&middot;&radic;L)</div>
            <div id="card-ss" class="text-base font-black tnum text-amber-800">1.340</div>
            <div class="text-[10px] text-gray-500 mt-0.5">Penyangga Risiko 95%</div>
        </div>

        <div class="p-3 rounded-xl bg-sage border border-matcha-100 text-center" style="background:#E7F5DC;">
            <div class="text-[11px] text-matcha-800 font-semibold mb-0.5">Rekomendasi Total Q</div>
            <div id="card-rec-q" class="text-base font-black tnum text-matcha-900">7.487</div>
            <div class="text-[10px] text-matcha-700 mt-0.5">Prediksi + SS - Sisa Stok</div>
        </div>
    </div>

    {{-- Kanvas Grafik Tren Historis 22 Periode --}}
    <div class="mt-2">
        <div class="flex items-center justify-between text-xs mb-2">
            <span class="font-bold text-gray-700 flex items-center gap-1.5">
                <i class="fa-solid fa-chart-area text-matcha-600"></i>
                Deret Waktu Pemakaian Riil LPLPO (2023-P1 s.d. 2026-P4) &amp; Proyeksi 2026-P5
            </span>
            <span id="card-censored-pill" class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 hidden">
                <i class="fa-solid fa-wand-magic-sparkles mr-1"></i>Rekonstruksi Censored Demand
            </span>
        </div>
        <div class="h-48 relative">
            <canvas id="quickInspectChart"></canvas>
        </div>
    </div>
</div>

{{-- ======================================================================
     4. SUB-MODUL: DISTRIBUSI MODEL & TOP 10 PENYAKIT ICD-10
     ====================================================================== --}}
<div id="distribusi-model" class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Distribusi Model Terbaik --}}
    <div class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-matcha-600"></i>
                Distribusi Algoritma Terbaik — 150 Sediaan Obat
            </h2>
            <span class="text-xs text-gray-400">Seleksi Error Terendah</span>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="w-48 h-48 relative shrink-0">
                <canvas id="modelDistChart"></canvas>
            </div>
            <div class="flex-1 space-y-2 w-full text-xs">
                @php
                    $palette = ['#1A3B22', '#5C6A44', '#728156', '#B6C99B', '#CFE1B9', '#3E4A2F'];
                @endphp
                @foreach($modelDist as $idx => $m)
                @php
                    $pct = round(($m->jumlah / $totalMedicines) * 100, 1);
                    $color = $palette[$idx % count($palette)];
                @endphp
                <div class="flex items-center justify-between p-2 rounded-lg bg-sage">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-3 h-3 rounded-full shrink-0" style="background:{{ $color }};"></span>
                        <span class="font-bold text-gray-800 truncate">{{ $m->model_terbaik }}</span>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="font-bold text-matcha-900 tnum">{{ $m->jumlah }} obat</span>
                        <span class="text-gray-400 text-[10px]">({{ $pct }}%)</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Top 10 Penyakit SIMPUS --}}
    <div class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-stethoscope text-matcha-600"></i>
                Top 10 Penyakit SIMPUS (31.125 Pasien Rawat Jalan)
            </h2>
            <span class="text-xs text-gray-400">ICD-10 Morbiditas</span>
        </div>
        <div class="h-56 relative">
            <canvas id="topDiseasesChart"></canvas>
        </div>
    </div>
</div>

{{-- ======================================================================
     5. SUB-MODUL: MATRIKS PARETO ABC-VEN & KERUGIAN ED
     ====================================================================== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Matriks Pareto ABC-VEN --}}
    <div id="matriks-pareto" class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-matcha-600"></i>
                Matriks Prioritas Klinis Pareto ABC-VEN
            </h2>
            <span class="text-xs font-semibold text-matcha-700">150 Sediaan</span>
        </div>

        <div class="space-y-3">
            @foreach($prioritasDist as $p)
            @php $pct = round(($p->jumlah / $totalMedicines) * 100, 1); @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-bold text-gray-800">{{ $p->prioritas_kategori }}</span>
                    <span class="font-black text-matcha-900 tnum">{{ $p->jumlah }} obat <span class="text-gray-400 font-normal">({{ $pct }}%)</span></span>
                </div>
                <div class="h-2.5 bg-matcha-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         style="width:{{ $pct }}%; background:{{ str_contains($p->prioritas_kategori, 'Prioritas 1') ? '#1A3B22' : (str_contains($p->prioritas_kategori, 'Prioritas 2') ? '#5C6A44' : '#728156') }};"></div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 formula-block text-xs leading-relaxed">
            <strong>Aturan Klasifikasi ABC-VEN:</strong><br>
            &bull; Prioritas 1: Vital &amp; Fast-Moving (Buffer SS Ketat 95%)<br>
            &bull; Prioritas 2: Essential &amp; Slow-Moving (Monitoring Berkala)<br>
            &bull; Prioritas 3: Non-Essential / Penunjang (Order Berbasis Permintaan)
        </div>
    </div>

    {{-- Audit Kerugian ED --}}
    <div id="kerugian-ed" class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-money-bill-trend-up text-amber-700"></i>
                Audit Kerugian Obat Kadaluarsa per Sumber Dana
            </h2>
            <span class="text-xs font-semibold text-amber-800">138 Item BA</span>
        </div>

        <div class="space-y-3.5">
            @php $totalEd = $edBySumber->sum('total') ?: 1; @endphp
            @foreach($edBySumber as $e)
            @php $pct = round(($e->total / $totalEd) * 100, 1); @endphp
            <div class="flex items-center gap-3">
                <div class="w-32 shrink-0 text-xs font-bold text-gray-700 truncate" title="{{ $e->sumber_anggaran }}">
                    {{ $e->sumber_anggaran }}
                </div>
                <div class="flex-1 h-7 bg-amber-50 rounded-lg overflow-hidden border border-amber-200 relative">
                    <div class="h-full rounded-lg transition-all duration-500" style="width:{{ $pct }}%; background:#B45309;"></div>
                    <span class="absolute inset-0 flex items-center px-2.5 text-xs font-bold text-amber-950">
                        Rp {{ number_format($e->total, 0, ',', '.') }}
                    </span>
                </div>
                <div class="text-xs text-gray-400 w-12 text-right shrink-0">{{ $e->items }} item</div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 p-3.5 rounded-xl text-xs flex items-start gap-2.5" style="background:#FEF3C7; border:1px solid #FCD34D; color:#78350F;">
            <i class="fa-solid fa-triangle-exclamation text-amber-700 mt-0.5 shrink-0 text-sm"></i>
            <div>
                <strong>Total Kerugian Akumulatif: Rp {{ number_format($totalEdLoss, 0, ',', '.') }}</strong><br>
                Baseline audit fisik membuktikan overstock pada periode historis tanpa peramalan saintifik menyebabkan penumpukan obat hingga kadaluarsa.
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     6. SUB-MODUL: TABEL EKSPLORASI TOP 10 PENYAKIT ICD-10
     ====================================================================== --}}
<div id="tabel-icd10" class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-table-list text-matcha-600"></i>
                Tabel Eksplorasi Top 10 Penyakit SIMPUS (Fase Data Understanding)
            </h2>
            <p class="text-gray-500 text-xs mt-0.5">Total 31.125 kunjungan pasien rawat jalan Puskesmas Teluk Sasah</p>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-matcha-100 text-matcha-800">
            SIMPUS 2023–2024
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#1A3B22; color:white;">
                    <th class="px-3 py-2.5 text-left rounded-tl-lg">#</th>
                    <th class="px-3 py-2.5 text-left">Kode ICD-10</th>
                    <th class="px-3 py-2.5 text-left">Nama Penyakit</th>
                    <th class="px-3 py-2.5 text-left">Klaster Klinis</th>
                    <th class="px-3 py-2.5 text-right">Laki-laki</th>
                    <th class="px-3 py-2.5 text-right">Perempuan</th>
                    <th class="px-3 py-2.5 text-right">Total Pasien</th>
                    <th class="px-3 py-2.5 text-right rounded-tr-lg">Distribusi (%)</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = $topDiseases->sum('grand_total') ?: 1; @endphp
                @foreach($topDiseases as $i => $d)
                @php $pct = round(($d->grand_total / $grandTotal) * 100, 1); @endphp
                <tr class="{{ $loop->even ? 'bg-matcha-50/50' : 'bg-white' }} hover:bg-matcha-100/70 transition-colors border-b border-matcha-100/40">
                    <td class="px-3 py-2 font-bold text-matcha-800">{{ $i + 1 }}</td>
                    <td class="px-3 py-2 font-mono font-bold" style="color:#1A3B22;">{{ $d->kode_icd }}</td>
                    <td class="px-3 py-2 font-semibold text-gray-800">{{ $d->nama_penyakit }}</td>
                    <td class="px-3 py-2 text-gray-500 text-[11px] max-w-[200px] truncate" title="{{ $d->klaster_icd }}">
                        {{ $d->klaster_icd }}
                    </td>
                    <td class="px-3 py-2 text-right tnum text-gray-600">{{ number_format($d->total_laki) }}</td>
                    <td class="px-3 py-2 text-right tnum text-gray-600">{{ number_format($d->total_perempuan) }}</td>
                    <td class="px-3 py-2 text-right font-black tnum text-matcha-900">{{ number_format($d->grand_total) }}</td>
                    <td class="px-3 py-2 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <div class="w-16 h-2 bg-matcha-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="width:{{ min(100, $pct * 2.5) }}%; background:#1A3B22;"></div>
                            </div>
                            <span class="font-bold tnum text-matcha-900 w-10 text-right">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ======================================================================
     7. SUB-MODUL: TABEL PROFIL STATISTIK 150 SEDIAAN OBAT
     ====================================================================== --}}
<div id="profil-statistik" class="lab-card p-5 mb-6 anim-fade">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-2 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-square-poll-vertical text-matcha-600"></i>
                Profil Statistik &amp; Skewness 150 Sediaan Obat LPLPO
            </h2>
            <p class="text-gray-500 text-xs mt-0.5">
                Rata-rata (&mu;), Standar Deviasi (&sigma;), Koefisien Variasi (CV = &sigma;/&mu;), &amp; Rasio Kekosongan Stok (SOH = 0)
            </p>
        </div>
        <div class="flex items-center gap-2">
            <input type="text" id="filter-profile-input" placeholder="Saring sediaan obat..."
                   class="text-xs border border-matcha-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-matcha-400 bg-sage w-48">
            <span class="text-xs text-gray-400 font-medium whitespace-nowrap">Total: 150 Item</span>
        </div>
    </div>

    <div class="overflow-x-auto max-h-[500px]">
        <table id="profile-table" class="w-full text-xs">
            <thead class="sticky top-0 z-10 shadow-sm">
                <tr style="background:#1A3B22; color:white;">
                    <th class="px-3 py-2.5 text-left rounded-tl-lg">#</th>
                    <th class="px-3 py-2.5 text-left">Nama Sediaan Obat</th>
                    <th class="px-3 py-2.5 text-center">Prioritas</th>
                    <th class="px-3 py-2.5 text-right">Mean (&mu;)</th>
                    <th class="px-3 py-2.5 text-right">Std (&sigma;)</th>
                    <th class="px-3 py-2.5 text-right">CV (%)</th>
                    <th class="px-3 py-2.5 text-right">Min / Max</th>
                    <th class="px-3 py-2.5 text-center">Stockout Rate</th>
                    <th class="px-3 py-2.5 text-center">Model Terbaik</th>
                    <th class="px-3 py-2.5 text-right">Akurasi (%)</th>
                    <th class="px-3 py-2.5 text-center rounded-tr-lg">Aksi</th>
                </tr>
            </thead>
            <tbody id="profile-table-body">
                @foreach($medicinesProfile as $i => $m)
                <tr class="{{ $loop->even ? 'bg-matcha-50/40' : 'bg-white' }} hover:bg-matcha-100 transition-colors border-b border-matcha-100/30">
                    <td class="px-3 py-2 font-bold text-matcha-800">{{ $i + 1 }}</td>
                    <td class="px-3 py-2">
                        <div class="font-semibold text-gray-800 max-w-[220px] truncate" title="{{ $m->nama_obat }}">
                            {{ $m->nama_obat }}
                        </div>
                        <div class="text-[10px] text-gray-400">Satuan: {{ $m->satuan }}</div>
                    </td>
                    <td class="px-3 py-2 text-center">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ str_contains($m->prioritas, 'Prioritas 1') ? 'bg-red-100 text-red-800' : (str_contains($m->prioritas, 'Prioritas 2') ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700') }}">
                            {{ str_contains($m->prioritas, 'Prioritas 1') ? 'P1 Vital' : (str_contains($m->prioritas, 'Prioritas 2') ? 'P2 Essensial' : 'P3 Non-Ess') }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum font-semibold text-matcha-900">{{ number_format($m->mean, 1) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum text-gray-600">{{ number_format($m->std, 1) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum">
                        <span class="font-bold {{ $m->cv > 100 ? 'text-red-700' : ($m->cv > 50 ? 'text-amber-700' : 'text-green-700') }}">
                            {{ number_format($m->cv, 1) }}%
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum text-[11px] text-gray-500">
                        {{ number_format($m->min) }} / {{ number_format($m->max) }}
                    </td>
                    <td class="px-3 py-2 text-center font-mono tnum">
                        @if($m->stockout_rate > 0)
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">
                                {{ $m->stockout_rate }}% ({{ 22 - $m->periode_aktif }}P)
                            </span>
                        @else
                            <span class="text-green-600 font-bold text-xs"><i class="fa-solid fa-check"></i> 0%</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-center">
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-matcha-100 text-matcha-800">
                            {{ $m->model_terbaik }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right font-bold tnum text-matcha-900">
                        {{ number_format($m->evaluasi_akurasi, 1) }}%
                    </td>
                    <td class="px-3 py-2 text-center">
                        <button onclick="loadDrugInspection({{ $m->id }}); document.getElementById('inspeksi-obat').scrollIntoView({behavior:'smooth'});"
                                class="px-2 py-1 rounded bg-matcha-700 hover:bg-matcha-800 text-white text-[10px] font-bold transition-all"
                                title="Inspeksi Cepat Sediaan Ini">
                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Inspeksi
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- 1. Donut Chart Distribusi Model Terbaik ----
(function () {
    const ctx = document.getElementById('modelDistChart');
    if (!ctx) return;
    const labels = @json($modelDist->pluck('model_terbaik'));
    const data   = @json($modelDist->pluck('jumlah'));
    const colors = ['#1A3B22', '#5C6A44', '#728156', '#B6C99B', '#CFE1B9', '#3E4A2F'];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ` ${ctx.label}: ${ctx.parsed} obat (${((ctx.parsed/150)*100).toFixed(1)}%)`
                    }
                }
            },
            cutout: '65%'
        }
    });
})();

// ---- 2. Top Diseases Horizontal Bar Chart ----
(function () {
    const ctx = document.getElementById('topDiseasesChart');
    if (!ctx) return;
    const rawLabels = @json($topDiseases->pluck('nama_penyakit'));
    const labels = rawLabels.map(n => n.length > 28 ? n.substring(0, 26) + '…' : n);
    const data   = @json($topDiseases->pluck('grand_total'));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Kunjungan Pasien',
                data: data,
                backgroundColor: data.map((_, i) => i === 0 ? '#1A3B22' : (i < 3 ? '#5C6A44' : '#CFE1B9')),
                borderRadius: 5,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ` ${ctx.parsed.x.toLocaleString('id-ID')} pasien rawat jalan`
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#E7F5DC' },
                    ticks: { font: { size: 9 }, callback: v => v.toLocaleString('id-ID') }
                },
                y: {
                    ticks: { font: { size: 9, family: 'Plus Jakarta Sans' } },
                    grid: { display: false }
                }
            }
        }
    });
})();

// ---- 3. Live Sparkline Inspeksi Cepat Sediaan Obat ----
let quickInspectChartInstance = null;

document.addEventListener('drugLoaded', function (e) {
    const d = e.detail;

    // Update elemen teks kartu inspeksi
    const nameEl      = document.getElementById('card-drug-name');
    const satuanEl    = document.getElementById('card-drug-satuan');
    const prioritasEl = document.getElementById('card-drug-prioritas');
    const momentumEl  = document.getElementById('card-drug-momentum');
    const modelEl     = document.getElementById('card-best-model');
    const accEl       = document.getElementById('card-model-acc');
    const predEl      = document.getElementById('card-pred-p5');
    const ssEl        = document.getElementById('card-ss');
    const recEl       = document.getElementById('card-rec-q');
    const censorPill  = document.getElementById('card-censored-pill');
    const lensBadge   = document.getElementById('card-lens-badge');

    if (nameEl)      nameEl.textContent = d.nama_obat;
    if (satuanEl)    satuanEl.textContent = `Satuan: ${d.satuan}`;
    if (prioritasEl) prioritasEl.textContent = d.prioritas;
    if (momentumEl)  momentumEl.textContent = d.momentum_status;
    if (modelEl)     modelEl.textContent = d.model_terbaik;
    if (accEl)       accEl.textContent = `Akurasi ${d.evaluasi_akurasi}% (MAPE ${d.mape}%)`;
    if (predEl)      predEl.textContent = d.prediksi_P5.toLocaleString('id-ID');
    if (ssEl)        ssEl.textContent = d.safety_stock.toLocaleString('id-ID');
    if (recEl)       recEl.textContent = d.rekomendasi.toLocaleString('id-ID');
    if (lensBadge)   lensBadge.textContent = d.lens === 'monthly' ? 'Lensa 36 Bulan' : '22 Periode Dwibulanan';

    if (censorPill) {
        if (d.is_censored) {
            censorPill.classList.remove('hidden');
        } else {
            censorPill.classList.add('hidden');
        }
    }

    // Render ulang sparkline
    const ctx = document.getElementById('quickInspectChart');
    if (!ctx) return;

    if (quickInspectChartInstance) {
        quickInspectChartInstance.destroy();
    }

    const labels   = d.chart.labels || [];
    const rawData  = d.chart.raw_y  || [];
    const wmaData  = d.chart.wma3   || [];
    const ssData   = d.chart.ss_line|| [];

    quickInspectChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemakaian LPLPO Riil',
                    data: rawData,
                    borderColor: '#9CA3AF',
                    borderDash: [4, 2],
                    backgroundColor: 'rgba(156,163,175,0.08)',
                    pointRadius: 3,
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Proyeksi WMA-3',
                    data: wmaData,
                    borderColor: '#1A3B22',
                    backgroundColor: 'rgba(26,59,34,0.1)',
                    pointRadius: 4,
                    pointBackgroundColor: '#1A3B22',
                    borderWidth: 2.5,
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Batas Safety Stock',
                    data: ssData,
                    borderColor: '#B45309',
                    borderDash: [3, 3],
                    pointRadius: 0,
                    borderWidth: 1.5,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 10, family: 'Plus Jakarta Sans' }, boxWidth: 12 }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: (c) => ` ${c.dataset.label}: ${(c.parsed.y || 0).toLocaleString('id-ID')}`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 }, maxRotation: 35 }
                },
                y: {
                    grid: { color: '#E7F5DC' },
                    ticks: { font: { size: 9 }, callback: v => v.toLocaleString('id-ID') }
                }
            }
        }
    });
});

// ---- 4. Filter Interaktif Tabel Profil Statistik ----
(function () {
    const input = document.getElementById('filter-profile-input');
    const tbody = document.getElementById('profile-table-body');
    if (!input || !tbody) return;

    input.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(r => {
            const text = r.textContent.toLowerCase();
            r.style.display = text.includes(q) ? '' : 'none';
        });
    });
})();
</script>
@endpush
