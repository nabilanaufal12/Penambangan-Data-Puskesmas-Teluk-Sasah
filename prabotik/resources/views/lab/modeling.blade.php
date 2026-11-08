@extends('layouts.lab')

@section('title', 'Arena Model & MACD')

@section('content')

{{-- ======================================================================
     1. SUB-MODUL: STATUS SINYAL MACD & KPI
     ====================================================================== --}}
<div id="status-macd" class="mb-6 anim-fade">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-matcha-900 flex items-center gap-2.5">
                <i class="fa-solid fa-chart-line text-matcha-700"></i>
                Arena Pemodelan Deret Waktu &amp; Osilator MACD
            </h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">
                Fase 4: Modeling &bull; Komparasi WMA-3 vs SMA-3 vs SES vs EMA &bull; Indikator Titik Balik Permintaan Obat
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold" style="background:#CFE1B9; color:#1A3B22;">
            <i class="fa-solid fa-chart-line"></i> Modeling Phase
        </span>
    </div>
</div>

{{-- Kartu Metrik Sinyal --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 mb-6 anim-fade">
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Momentum Bullish (Meningkat)</div>
        <div class="text-2xl font-black tnum" style="color:#1A3B22;">{{ number_format($macdStats->bullish_count ?? 0) }}</div>
        <div class="text-xs text-green-700 font-semibold mt-0.5">Histogram MACD &gt; 0</div>
    </div>
    <div class="lab-card p-4 border-l-4 border-l-red-500">
        <div class="text-xs text-gray-500 mb-1">Momentum Bearish (Menurun)</div>
        <div class="text-2xl font-black tnum text-red-700">{{ number_format($macdStats->bearish_count ?? 0) }}</div>
        <div class="text-xs text-red-600 font-semibold mt-0.5">Histogram MACD &lt; 0</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#5C6A44;">
        <div class="text-xs text-gray-500 mb-1">Rata-rata Akurasi Semua Obat</div>
        <div class="text-2xl font-black tnum" style="color:#5C6A44;">{{ number_format($macdStats->avg_accuracy ?? 0, 1) }}%</div>
        <div class="text-xs text-matcha-700 mt-0.5 font-semibold">150 Sediaan LPLPO</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Akurasi Terbaik Tunggal</div>
        <div class="text-2xl font-black tnum text-matcha-900">{{ number_format($macdStats->max_accuracy ?? 0, 1) }}%</div>
        <div class="text-xs text-gray-500 mt-0.5">Model Terpilih</div>
    </div>
</div>

{{-- ======================================================================
     2. SUB-MODUL: DUAL-PANEL SYNCHRONIZED CHARTS (ARENA + MACD)
     ====================================================================== --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">

    {{-- Panel Kiri: Arena 4 Model --}}
    <div id="arena-komparasi" class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-matcha-100">
            <div>
                <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                    <i class="fa-solid fa-swords text-matcha-600"></i>
                    Arena Komparasi 4 Model Deret Waktu
                </h2>
                <div id="arena-drug-name" class="text-xs font-semibold text-gray-500 mt-0.5">
                    Parasetamol tablet 500 mg
                </div>
            </div>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-matcha-100 text-matcha-800">
                LPLPO 22 Periode
            </span>
        </div>

        <div class="h-60 relative">
            <canvas id="arenaChart"></canvas>
        </div>

        {{-- Legenda Interaktif --}}
        <div class="mt-3 flex flex-wrap items-center gap-3 text-xs border-t border-matcha-100 pt-2.5">
            <span class="flex items-center gap-1.5 font-bold" style="color:#1A3B22;">
                <span class="inline-block w-4 h-1 rounded" style="background:#1A3B22;"></span>
                WMA-3 (Utama, w=[3,2,1])
            </span>
            <span class="flex items-center gap-1.5 text-gray-700">
                <span class="inline-block w-4 h-0.5 bg-matcha-700 border-t border-dashed"></span>
                SMA-3 (Baseline Rata-rata)
            </span>
            <span class="flex items-center gap-1.5 text-amber-800">
                <span class="inline-block w-4 h-0.5 bg-amber-600 border-t border-dotted"></span>
                SES (&alpha;=0,3)
            </span>
            <span class="flex items-center gap-1.5 text-gray-500">
                <span class="inline-block w-4 h-0.5 bg-gray-400"></span>
                Aktual Riil
            </span>
        </div>
    </div>

    {{-- Panel Kanan: Osilator Momentum MACD --}}
    <div id="osilator-macd" class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-matcha-100">
            <div>
                <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-simple text-matcha-600"></i>
                    Osilator Momentum MACD Adaptasi Logistik
                </h2>
                <div id="macd-params-text" class="text-xs font-semibold text-gray-500 mt-0.5">
                    Parameter: MACD (Fast-2, Slow-4, Signal-2)
                </div>
            </div>
            <div id="macd-status-pill" class="text-xs px-2.5 py-1 rounded-full font-bold"></div>
        </div>

        <div class="h-60 relative">
            <canvas id="macdChart"></canvas>
        </div>

        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-xs border-t border-matcha-100 pt-2.5 text-gray-600">
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded bg-green-700/70"></span>
                    Histogram Positif (Permintaan Meningkat)
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded bg-red-600/70"></span>
                    Histogram Negatif (Penurunan)
                </span>
            </div>
            <div class="font-mono text-[11px] text-matcha-800">
                MACD = EMA_{fast} - EMA_{slow}
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     3. SUB-MODUL: FORMULASI 4 ALGORITMA
     ====================================================================== --}}
<div id="formulasi-algoritma" class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
        <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
            <i class="fa-solid fa-square-root-variable text-matcha-600"></i>
            Formulasi Matematis 4 Model Deret Waktu yang Diadu
        </h2>
        <span class="text-xs text-matcha-700 font-semibold">Komparasi Ilmiah Sidang</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- WMA-3 --}}
        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-2 flex items-center justify-between" style="background:#1A3B22;">
                <span class="text-white font-bold text-xs">1. WMA-3 (Model Utama)</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/20 text-white font-bold">Produksi</span>
            </div>
            <div class="p-3.5 formula-block rounded-none border-0 text-xs leading-relaxed">
                &ycirc;_{t+1} = (3y_t + 2y_{t-1} + y_{t-2}) / 6<br><br>
                Bobot linier menurun w=[3, 2, 1]. Data terkini diberi penekanan 50% bobot total.
            </div>
        </div>

        {{-- SMA-3 --}}
        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-2 flex items-center justify-between" style="background:#5C6A44;">
                <span class="text-white font-bold text-xs">2. SMA-3 (Baseline 1)</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/20 text-white">Uniform</span>
            </div>
            <div class="p-3.5 formula-block rounded-none border-0 text-xs leading-relaxed">
                &ycirc;_{t+1} = (y_t + y_{t-1} + y_{t-2}) / 3<br><br>
                Rata-rata bergerak sederhana bobot merata (33,3% per periode) tanpa adaptasi tren.
            </div>
        </div>

        {{-- SES --}}
        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-2 flex items-center justify-between" style="background:#B45309;">
                <span class="text-white font-bold text-xs">3. SES (&alpha;=0,3) (Baseline 2)</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/20 text-white">Exponential</span>
            </div>
            <div class="p-3.5 formula-block rounded-none border-0 text-xs leading-relaxed">
                S_t = 0,3y_t + 0,7S_{t-1}<br><br>
                Peluruhan eksponensial dengan parameter penghalus &alpha; = 0,3 (70% memori historis).
            </div>
        </div>

        {{-- EMA --}}
        <div class="rounded-xl overflow-hidden border border-matcha-200">
            <div class="px-3.5 py-2 flex items-center justify-between" style="background:#3E4A2F;">
                <span class="text-white font-bold text-xs">4. EMA-k (Dasar MACD)</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/20 text-white">Momentum</span>
            </div>
            <div class="p-3.5 formula-block rounded-none border-0 text-xs leading-relaxed">
                EMA_t = y_t &times; k + EMA_{t-1}(1 - k)<br>
                k = 2 / (span + 1)<br><br>
                Digunakan untuk mengekstrak sinyal percepatan permintaan stok obat.
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     4. SUB-MODUL: LEADERBOARD WAPE BENCHMARK MODEL
     ====================================================================== --}}
<div id="leaderboard-wape" class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i>
                Leaderboard WAPE — Komparasi ML vs. Baseline Statistik
            </h2>
            <span class="text-xs text-gray-400">&darr; WAPE Lebih Rendah = Lebih Baik</span>
        </div>
        <div class="h-56 relative">
            <canvas id="benchmarkChart"></canvas>
        </div>
    </div>

    <div class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-medal text-matcha-600"></i>
                Top 10 Sediaan dengan Akurasi Simetris Tertinggi
            </h2>
            <span class="text-xs text-matcha-700 font-semibold">Bounded Symmetric</span>
        </div>
        <div class="overflow-x-auto max-h-56">
            <table class="w-full text-xs">
                <thead class="sticky top-0 bg-matcha-800 text-white shadow-sm">
                    <tr>
                        <th class="px-2.5 py-1.5 text-left">Nama Obat</th>
                        <th class="px-2 py-1.5 text-center">Model</th>
                        <th class="px-2 py-1.5 text-right">Akurasi</th>
                        <th class="px-2 py-1.5 text-right">MAPE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topAccuracy->take(10) as $m)
                    <tr class="{{ $loop->even ? 'bg-matcha-50/50' : 'bg-white' }} border-b border-matcha-100/30">
                        <td class="px-2.5 py-1.5 font-medium text-gray-800 max-w-[150px] truncate" title="{{ $m->nama_obat }}">
                            {{ $m->nama_obat }}
                        </td>
                        <td class="px-2 py-1.5 text-center">
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-matcha-100 text-matcha-900">
                                {{ $m->model_terbaik }}
                            </span>
                        </td>
                        <td class="px-2 py-1.5 text-right font-black tnum text-matcha-900">
                            {{ number_format($m->evaluasi_akurasi, 1) }}%
                        </td>
                        <td class="px-2 py-1.5 text-right font-mono tnum text-gray-500">
                            {{ number_format($m->mape_terbaik, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================================================================
     5. SUB-MODUL: TABEL JEJAK HITUNG STEP-BY-STEP (UNTUK DOSEN PENGUJI)
     ====================================================================== --}}
<div id="tabel-jejak-step" class="lab-card p-5 mb-6 anim-fade" style="border-top:4px solid #1A3B22;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-2 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-calculator text-matcha-600"></i>
                Tabel Jejak Komputasi Step-by-Step (Verifikasi Sidang Dosen)
            </h2>
            <p id="trace-drug-title" class="text-xs text-gray-500 mt-0.5">
                Perhitungan transparan WMA-3 per periode dwibulanan untuk sediaan aktif
            </p>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-matcha-100 text-matcha-800">
            Formula: (3y_t + 2y_{t-1} + y_{t-2}) / 6
        </span>
    </div>

    <div class="overflow-x-auto max-h-72">
        <table class="w-full text-xs">
            <thead class="sticky top-0 bg-matcha-900 text-white shadow-sm">
                <tr>
                    <th class="px-3 py-2 text-left rounded-tl">Periode Target</th>
                    <th class="px-3 py-2 text-center">Window Historis (y_{t-2}, y_{t-1}, y_t)</th>
                    <th class="px-3 py-2 text-left">Substitusi Rumus Bobot [1, 2, 3]</th>
                    <th class="px-3 py-2 text-right">Aktual</th>
                    <th class="px-3 py-2 text-right">Prediksi WMA-3</th>
                    <th class="px-3 py-2 text-right">Residual (&epsilon;)</th>
                    <th class="px-3 py-2 text-right rounded-tr">Akurasi BSA</th>
                </tr>
            </thead>
            <tbody id="trace-table-body">
                <tr>
                    <td colspan="7" class="px-3 py-6 text-center text-gray-400 italic">
                        Memuat jejak komputasi sediaan aktif...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ======================================================================
     6. SUB-MODUL: MOMENTUM BULLISH & BEARISH
     ====================================================================== --}}
<div id="daftar-momentum" class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    {{-- Bullish --}}
    <div class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-matcha-100">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-arrow-trend-up text-green-700"></i>
                Top 15 Momentum Bullish Terkuat (Sinyal Lonjakan Permintaan)
            </h2>
            <span class="text-xs text-green-800 font-bold">Histogram &gt; 0</span>
        </div>
        <div class="overflow-x-auto max-h-60">
            <table class="w-full text-xs">
                <thead class="sticky top-0 bg-matcha-900 text-white">
                    <tr>
                        <th class="px-2.5 py-1.5 text-left">Nama Sediaan</th>
                        <th class="px-2 py-1.5 text-right">MACD</th>
                        <th class="px-2 py-1.5 text-right">Signal</th>
                        <th class="px-2 py-1.5 text-right">Histogram</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bullishMeds->take(15) as $m)
                    <tr class="{{ $loop->even ? 'bg-matcha-50/50' : 'bg-white' }} border-b border-matcha-100/30">
                        <td class="px-2.5 py-1.5 font-medium text-gray-800 max-w-[160px] truncate" title="{{ $m->nama_obat }}">
                            {{ $m->nama_obat }}
                        </td>
                        <td class="px-2 py-1.5 text-right font-mono tnum text-matcha-900">{{ number_format($m->macd_value, 1) }}</td>
                        <td class="px-2 py-1.5 text-right font-mono tnum text-gray-500">{{ number_format($m->signal_line, 1) }}</td>
                        <td class="px-2 py-1.5 text-right font-mono tnum font-bold text-green-800">+{{ number_format($m->histogram, 1) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bearish --}}
    <div class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-matcha-100">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-arrow-trend-down text-red-600"></i>
                Top 15 Momentum Bearish (Risiko Penurunan Permintaan)
            </h2>
            <span class="text-xs text-red-700 font-bold">Histogram &lt; 0</span>
        </div>
        <div class="overflow-x-auto max-h-60">
            <table class="w-full text-xs">
                <thead class="sticky top-0 bg-red-800 text-white">
                    <tr>
                        <th class="px-2.5 py-1.5 text-left">Nama Sediaan</th>
                        <th class="px-2 py-1.5 text-right">MACD</th>
                        <th class="px-2 py-1.5 text-right">Signal</th>
                        <th class="px-2 py-1.5 text-right">Histogram</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bearishMeds->take(15) as $m)
                    <tr class="{{ $loop->even ? 'bg-red-50/40' : 'bg-white' }} border-b border-red-100/30">
                        <td class="px-2.5 py-1.5 font-medium text-gray-800 max-w-[160px] truncate" title="{{ $m->nama_obat }}">
                            {{ $m->nama_obat }}
                        </td>
                        <td class="px-2 py-1.5 text-right font-mono tnum text-red-700">{{ number_format($m->macd_value, 1) }}</td>
                        <td class="px-2 py-1.5 text-right font-mono tnum text-gray-500">{{ number_format($m->signal_line, 1) }}</td>
                        <td class="px-2 py-1.5 text-right font-mono tnum font-bold text-red-800">{{ number_format($m->histogram, 1) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- 1. Leaderboard WAPE Benchmark ----
(function () {
    const ctx = document.getElementById('benchmarkChart');
    if (!ctx) return;
    const benchmarks = @json($benchmarks);
    const labels = benchmarks.map(b => b.model_name.length > 22 ? b.model_name.substring(0, 20) + '…' : b.model_name);
    const wapes  = benchmarks.map(b => parseFloat(b.wape));
    const colors = wapes.map((w, i) => i === 0 ? '#1A3B22' : (w <= 0.65 ? '#5C6A44' : '#CFE1B9'));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'WAPE (Galat Relatif)',
                data: wapes,
                backgroundColor: colors,
                borderRadius: 4
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
                        label: c => ` WAPE: ${(c.parsed.x * 100).toFixed(1)}%`
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#E7F5DC' },
                    ticks: { font: { size: 9 }, callback: v => (v * 100).toFixed(0) + '%' }
                },
                y: {
                    ticks: { font: { size: 9 } },
                    grid: { display: false }
                }
            }
        }
    });
})();

// ---- 2. Dual-Panel Arena & MACD Live Charts ----
let arenaChartInstance = null;
let macdChartInstance  = null;

document.addEventListener('drugLoaded', function (e) {
    const d = e.detail;

    // Judul & Badge
    const drugNameEl = document.getElementById('arena-drug-name');
    const traceTitle = document.getElementById('trace-drug-title');
    const paramsText = document.getElementById('macd-params-text');
    const pill       = document.getElementById('macd-status-pill');

    if (drugNameEl) drugNameEl.textContent = `${d.nama_obat} (${d.satuan})`;
    if (traceTitle) traceTitle.textContent = `Perhitungan transparan WMA-3 per periode untuk ${d.nama_obat}`;

    if (d.chart && d.chart.macd && paramsText) {
        paramsText.textContent = `Parameter: MACD ${d.chart.macd.params}`;
    }

    if (pill) {
        const isBullish = d.histogram >= 0;
        pill.style.background = isBullish ? '#D1FAE5' : '#FEE2E2';
        pill.style.color      = isBullish ? '#065F46' : '#991B1B';
        pill.textContent = isBullish ? '📈 Bullish Momentum' : '📉 Bearish Momentum';
    }

    // 1. Arena Multi-Model Chart
    const arenaCtx = document.getElementById('arenaChart');
    if (arenaCtx) {
        if (arenaChartInstance) arenaChartInstance.destroy();

        arenaChartInstance = new Chart(arenaCtx, {
            type: 'line',
            data: {
                labels: d.chart.labels,
                datasets: [
                    {
                        label: 'Aktual Tercatat LPLPO',
                        data: d.chart.raw_y,
                        borderColor: '#9CA3AF',
                        borderDash: [5, 3],
                        pointRadius: 3,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'WMA-3 (Utama, w=[3,2,1])',
                        data: d.chart.wma3,
                        borderColor: '#1A3B22',
                        backgroundColor: 'rgba(26,59,34,0.1)',
                        pointRadius: 4,
                        pointBackgroundColor: '#1A3B22',
                        borderWidth: 2.5,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'SMA-3 (Rata-rata 3 Periode)',
                        data: d.chart.sma3,
                        borderColor: '#5C6A44',
                        borderDash: [4, 2],
                        pointRadius: 3,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'SES (alpha=0.3)',
                        data: d.chart.ses,
                        borderColor: '#B45309',
                        borderDash: [2, 2],
                        pointRadius: 3,
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: c => ` ${c.dataset.label}: ${(c.parsed.y || 0).toLocaleString('id-ID')}`
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
    }

    // 2. MACD Synchronized Chart
    const macdCtx = document.getElementById('macdChart');
    if (macdCtx && d.chart && d.chart.macd) {
        if (macdChartInstance) macdChartInstance.destroy();

        const macdInfo  = d.chart.macd;
        const histData  = macdInfo.histogram || [];
        const lineData  = macdInfo.macd_line || [];
        const sigData   = macdInfo.signal    || [];

        macdChartInstance = new Chart(macdCtx, {
            data: {
                labels: macdInfo.labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'MACD Histogram',
                        data: histData,
                        backgroundColor: histData.map(v => v >= 0 ? 'rgba(26,59,34,0.65)' : 'rgba(220,38,38,0.65)'),
                        borderRadius: 2
                    },
                    {
                        type: 'line',
                        label: 'Garis MACD',
                        data: lineData,
                        borderColor: '#1A3B22',
                        borderWidth: 2,
                        pointRadius: 2,
                        tension: 0.3,
                        fill: false
                    },
                    {
                        type: 'line',
                        label: 'Garis Signal',
                        data: sigData,
                        borderColor: '#B45309',
                        borderDash: [3, 2],
                        borderWidth: 1.5,
                        pointRadius: 2,
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 9 }, maxRotation: 35 }
                    },
                    y: {
                        grid: { color: '#E7F5DC' },
                        ticks: { font: { size: 9 } }
                    }
                }
            }
        });
    }

    // 3. Render Tabel Jejak Hitung Step-by-Step
    const traceBody = document.getElementById('trace-table-body');
    if (traceBody && d.trace && d.trace.length) {
        let html = '';
        d.trace.forEach((row, i) => {
            const accColor = row.accuracy >= 80 ? 'text-green-800' : (row.accuracy >= 60 ? 'text-matcha-800' : 'text-amber-800');
            html += `
                <tr class="${i % 2 === 0 ? 'bg-white' : 'bg-matcha-50/40'} border-b border-matcha-100/30 hover:bg-matcha-100 transition-colors">
                    <td class="px-3 py-2 font-bold text-matcha-900">${row.periode}</td>
                    <td class="px-3 py-2 text-center font-mono tnum text-gray-600">[${row.window}]</td>
                    <td class="px-3 py-2 font-mono text-[11px] text-gray-700">${row.formula}</td>
                    <td class="px-3 py-2 text-right font-mono tnum text-gray-800">${Number(row.actual).toLocaleString('id-ID')}</td>
                    <td class="px-3 py-2 text-right font-mono tnum font-bold text-matcha-900">${Number(row.wma3).toLocaleString('id-ID')}</td>
                    <td class="px-3 py-2 text-right font-mono tnum ${row.residual >= 0 ? 'text-green-700' : 'text-red-700'}">${row.residual >= 0 ? '+' : ''}${row.residual}</td>
                    <td class="px-3 py-2 text-right font-bold tnum ${accColor}">${row.accuracy}%</td>
                </tr>
            `;
        });
        traceBody.innerHTML = html;
    }
});
</script>
@endpush
