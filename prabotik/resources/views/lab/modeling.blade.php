@extends('layouts.lab')

@section('title', 'Arena Model & MACD')

@section('content')
{{-- ===== HEADER ===== --}}
<div class="mb-6 anim-fade">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-matcha-700"></i>
                Arena Model & Osilator Momentum MACD
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Fase 4: Modeling — WMA-3 vs SMA-3 vs SES(α=0,3) vs EMA · MACD (Fast/Slow/Signal)
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold" style="background:#CFE1B9; color:#1A3B22;">
            <i class="fa-solid fa-chart-line"></i> Modeling Phase
        </span>
    </div>
</div>

{{-- ===== KARTU STATISTIK MACD ===== --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6 anim-fade">
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Momentum Bullish</div>
        <div class="text-2xl font-bold tnum" style="color:#1A3B22;">{{ number_format($macdStats->bullish_count ?? 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">Histogram MACD > 0</div>
    </div>
    <div class="lab-card p-4 border-l-4 border-l-red-400">
        <div class="text-xs text-gray-500 mb-1">Momentum Bearish</div>
        <div class="text-2xl font-bold tnum text-red-600">{{ number_format($macdStats->bearish_count ?? 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">Histogram MACD &lt; 0</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#5C6A44;">
        <div class="text-xs text-gray-500 mb-1">Rata-rata Akurasi</div>
        <div class="text-2xl font-bold tnum" style="color:#5C6A44;">{{ number_format($macdStats->avg_accuracy ?? 0, 1) }}%</div>
        <div class="text-xs text-gray-400 mt-1">Semua 150 Obat</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#3E4A2F;">
        <div class="text-xs text-gray-500 mb-1">Akurasi Tertinggi</div>
        <div class="text-2xl font-bold tnum" style="color:#3E4A2F;">{{ number_format($macdStats->max_accuracy ?? 0, 1) }}%</div>
        <div class="text-xs text-gray-400 mt-1">Model Terbaik Tunggal</div>
    </div>
</div>

{{-- ===== DUAL PANEL: Grafik Peramalan + MACD (Drug Switcher) ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">
    {{-- Panel 1: Model Arena Chart --}}
    <div class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-swords text-matcha-600"></i>
                Komparasi 4 Model Peramalan
            </h2>
            <div id="arena-drug-name" class="text-xs text-gray-400">← Pilih obat di atas</div>
        </div>
        <div class="h-56 relative">
            <canvas id="arenaChart"></canvas>
            <div id="arena-placeholder" class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-gray-200">
                    <i class="fa-solid fa-swords text-3xl mb-2"></i>
                    <div class="text-xs">Pilih sediaan obat untuk memuat arena perbandingan model</div>
                </div>
            </div>
        </div>
        {{-- Legenda --}}
        <div class="mt-3 flex flex-wrap gap-3 text-xs">
            <span class="flex items-center gap-1.5"><span class="inline-block w-5 h-0.5 rounded" style="background:#1A3B22;"></span>WMA-3 (Utama)</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-5 h-0.5 rounded" style="background:#728156; border-top:2px dashed #728156;"></span>SMA-3</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-5 h-0.5 rounded" style="background:#B45309; border-top:2px dotted #B45309;"></span>SES(α=0.3)</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-5 h-0.5 rounded" style="background:#CFE1B9;"></span>EMA</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-5 h-0.5 rounded border-t-2 border-dashed border-gray-400"></span>Aktual</span>
        </div>
    </div>

    {{-- Panel 2: MACD Osilator --}}
    <div class="lab-card p-5 anim-fade">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-wave-square text-matcha-600"></i>
                Osilator Momentum MACD
            </h2>
            <div id="macd-status-badge" class="hidden text-xs px-2 py-0.5 rounded-full font-semibold"></div>
        </div>
        <div class="h-56 relative">
            <canvas id="macdChart"></canvas>
            <div id="macd-placeholder" class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-gray-200">
                    <i class="fa-solid fa-wave-square text-3xl mb-2"></i>
                    <div class="text-xs">MACD = EMA_fast − EMA_slow · Signal = EMA(MACD)</div>
                </div>
            </div>
        </div>
        {{-- Formulasi MACD --}}
        <div class="mt-3 formula-block text-xs">
            Lensa Bulanan: MACD(12,26,9) · Lensa Dwibulanan: MACD(2,4,2)<br>
            MACD_t = EMA_fast_t − EMA_slow_t · Histogram = MACD − Signal
        </div>
    </div>
</div>

{{-- ===== RUMUS MODEL ===== --}}
<div class="lab-card p-5 mb-6 anim-fade">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-square-root-variable text-matcha-600"></i>
        Formulasi Matematis — 4 Model Peramalan yang Diadu
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $formulas = [
                ['name'=>'WMA-3','color'=>'#1A3B22','badge'=>'Model Utama','formula'=>"ŷ_t = Σ(w_i · y_{t-i}) / Σw\nw = [3, 2, 1] → bobot\nlebih recent = lebih berat"],
                ['name'=>'SMA-3','color'=>'#728156','badge'=>'Baseline','formula'=>"ŷ_t = (y_{t-1} + y_{t-2} + y_{t-3}) / 3\n\nRata-rata sederhana 3 periode\ntanpa pembobotan"],
                ['name'=>'SES (α=0.3)','color'=>'#B45309','badge'=>'Baseline','formula'=>"S_t = α·y_t + (1−α)·S_{t-1}\nα = 0.3 (dampening 70%)\n\nMeluruh eksponensial"],
                ['name'=>'EMA','color'=>'#5C6A44','badge'=>'Baseline','formula'=>"EMA_t = y_t·k + EMA_{t-1}·(1−k)\nk = 2/(span+1)\n\nDasar perhitungan MACD"],
            ];
        @endphp
        @foreach($formulas as $f)
        <div class="rounded-xl overflow-hidden border border-matcha-100">
            <div class="px-3 py-2 flex items-center justify-between" style="background:{{ $f['color'] }};">
                <span class="text-white font-bold text-sm">{{ $f['name'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(255,255,255,0.2); color:white;">{{ $f['badge'] }}</span>
            </div>
            <div class="p-3 formula-block rounded-none border-0 text-xs whitespace-pre-line">{{ $f['formula'] }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== LEADERBOARD MODEL BENCHMARK ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-trophy text-amber-500"></i>
            Leaderboard Model — WAPE Benchmark
        </h2>
        <div class="h-48">
            <canvas id="benchmarkChart"></canvas>
        </div>
    </div>

    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-matcha-600"></i>
            Distribusi Model Terbaik — 150 Obat
        </h2>
        <div class="h-48">
            <canvas id="modelDistChart"></canvas>
        </div>
    </div>
</div>

{{-- ===== TABEL LEADERBOARD AKURASI ===== --}}
<div class="lab-card p-5 anim-fade mb-6">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-medal text-matcha-600"></i>
        Top 15 Obat — Akurasi Model Tertinggi
    </h2>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#1A3B22; color:white;">
                    <th class="px-3 py-2 text-left rounded-tl-lg">#</th>
                    <th class="px-3 py-2 text-left">Nama Obat</th>
                    <th class="px-3 py-2 text-center">Model Terbaik</th>
                    <th class="px-3 py-2 text-right">Akurasi BSA (%)</th>
                    <th class="px-3 py-2 text-right">MAPE (%)</th>
                    <th class="px-3 py-2 text-right">Prediksi P5</th>
                    <th class="px-3 py-2 text-right rounded-tr-lg">Momentum</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topAccuracy as $i => $m)
                <tr class="{{ $loop->even ? 'bg-matcha-50' : 'bg-white' }} hover:bg-matcha-100 transition-colors">
                    <td class="px-3 py-2 font-bold text-matcha-700">{{ $i + 1 }}</td>
                    <td class="px-3 py-2 text-gray-800 max-w-[160px] truncate font-medium" title="{{ $m->nama_obat }}">{{ $m->nama_obat }}</td>
                    <td class="px-3 py-2 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#E7F5DC; color:#3E4A2F;">{{ $m->model_terbaik }}</span>
                    </td>
                    <td class="px-3 py-2 text-right">
                        @php $a = $m->evaluasi_akurasi; $c = $a>=80?'#1A3B22':($a>=60?'#5C6A44':'#B45309'); @endphp
                        <span class="font-bold tnum" style="color:{{ $c }};">{{ number_format($a,1) }}</span>
                    </td>
                    <td class="px-3 py-2 text-right tnum text-gray-500">{{ number_format($m->mape_terbaik,1) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum text-matcha-800 font-semibold">{{ number_format($m->prediksi_P5) }}</td>
                    <td class="px-3 py-2 text-right text-xs">
                        @php $h = strpos($m->momentum_status, 'Bullish') !== false || strpos($m->momentum_status, 'Meningkat') !== false; @endphp
                        <span class="px-1.5 py-0.5 rounded text-xs {{ $h ? 'badge-bullish' : 'badge-bearish' }}">
                            {{ $h ? '📈 Bullish' : '📉 Bearish' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ===== TABEL MACD MOMENTUM ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-arrow-trend-up text-matcha-700"></i>
            Top 15 Momentum Bullish Terkuat
        </h2>
        <div class="overflow-auto max-h-56">
            <table class="w-full text-xs">
                <thead><tr style="background:#1A3B22; color:white;">
                    <th class="px-2 py-1.5 text-left rounded-tl">Obat</th>
                    <th class="px-2 py-1.5 text-right">MACD</th>
                    <th class="px-2 py-1.5 text-right rounded-tr">Hist.</th>
                </tr></thead>
                <tbody>
                    @foreach($bullishMeds->take(15) as $m)
                    <tr class="{{ $loop->even ? 'bg-matcha-50' : 'bg-white' }}">
                        <td class="px-2 py-1.5 max-w-[130px] truncate" title="{{ $m->nama_obat }}">{{ $m->nama_obat }}</td>
                        <td class="px-2 py-1.5 text-right font-mono tnum" style="color:#1A3B22;">{{ number_format($m->macd_value,1) }}</td>
                        <td class="px-2 py-1.5 text-right font-mono tnum font-bold" style="color:#065F46;">+{{ number_format($m->histogram,1) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-arrow-trend-down text-red-600"></i>
            Momentum Bearish (Risiko Penurunan Demand)
        </h2>
        <div class="overflow-auto max-h-56">
            <table class="w-full text-xs">
                <thead><tr class="bg-red-700 text-white">
                    <th class="px-2 py-1.5 text-left rounded-tl">Obat</th>
                    <th class="px-2 py-1.5 text-right">MACD</th>
                    <th class="px-2 py-1.5 text-right rounded-tr">Hist.</th>
                </tr></thead>
                <tbody>
                    @foreach($bearishMeds->take(15) as $m)
                    <tr class="{{ $loop->even ? 'bg-red-50' : 'bg-white' }}">
                        <td class="px-2 py-1.5 max-w-[130px] truncate" title="{{ $m->nama_obat }}">{{ $m->nama_obat }}</td>
                        <td class="px-2 py-1.5 text-right font-mono tnum text-red-600">{{ number_format($m->macd_value,1) }}</td>
                        <td class="px-2 py-1.5 text-right font-mono tnum font-bold text-red-700">{{ number_format($m->histogram,1) }}</td>
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
// ---- Benchmark WAPE Chart ----
(function () {
    const ctx = document.getElementById('benchmarkChart');
    if (!ctx) return;
    const benchmarks = @json($benchmarks);
    const labels = benchmarks.map(b => b.model_name.length > 20 ? b.model_name.substring(0,18)+'…' : b.model_name);
    const wapes  = benchmarks.map(b => parseFloat(b.wape));
    const colors = wapes.map((w, i) => w <= wapes[0]*1.1 ? '#1A3B22' : (w <= 0.65 ? '#5C6A44' : '#CFE1B9'));
    new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets: [{ label: 'WAPE (↓ lebih baik)', data: wapes, backgroundColor: colors, borderRadius: 4 }] },
        options: {
            indexAxis: 'y', responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false },
                tooltip: { callbacks: { label: c => ` WAPE: ${(c.parsed.x*100).toFixed(1)}%` } } },
            scales: {
                x: { grid: { color: '#E7F5DC' }, ticks: { font: { size: 9 }, callback: v => (v*100).toFixed(0)+'%' } },
                y: { ticks: { font: { size: 9 } }, grid: { display: false } }
            }
        }
    });
})();

// ---- Model Distribution Pie ----
(function () {
    const ctx = document.getElementById('modelDistChart');
    if (!ctx) return;
    const dist = @json($modelDist);
    const colors = ['#1A3B22','#5C6A44','#728156','#CFE1B9','#B6C99B','#3E4A2F'];
    new Chart(ctx, {
        type: 'doughnut',
        data: { labels: dist.map(d=>d.model_terbaik), datasets: [{ data: dist.map(d=>d.jumlah), backgroundColor: colors, borderWidth: 2, borderColor:'#fff' }] },
        options: { responsive: true, maintainAspectRatio: false, cutout: '60%',
            plugins: { legend: { position:'right', labels: { font:{size:9}, boxWidth:8, padding:6 } },
                tooltip: { callbacks: { label: c => ` ${c.label}: ${c.parsed} obat` } } } }
    });
})();

// ---- Arena & MACD Charts (from drug switcher) ----
let arenaChart = null, macdChart = null;

document.addEventListener('drugLoaded', function (e) {
    const data = e.detail;

    // Update drug name
    const nameEl = document.getElementById('arena-drug-name');
    if (nameEl) nameEl.textContent = data.nama_obat;

    // Hide placeholders
    ['arena-placeholder','macd-placeholder'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    // Arena Chart
    const arenaCtx = document.getElementById('arenaChart');
    if (arenaCtx) {
        if (arenaChart) arenaChart.destroy();
        arenaChart = new Chart(arenaCtx, {
            type: 'line',
            data: {
                labels: data.chart.labels,
                datasets: [
                    { label: 'Aktual', data: data.chart.actual, borderColor:'#9CA3AF', borderDash:[5,3], pointRadius:4, tension:0.3, spanGaps:true },
                    { label: 'WMA-3 (Utama)', data: data.chart.wma3, borderColor:'#1A3B22', backgroundColor:'rgba(26,59,34,0.1)', fill:true, pointRadius:5, tension:0.4, spanGaps:true, borderWidth:2.5 },
                    { label: 'SMA-3', data: data.chart.sma3, borderColor:'#728156', borderDash:[4,2], pointRadius:3, tension:0.3, spanGaps:true },
                    { label: 'SES(α=0.3)', data: data.chart.ses, borderColor:'#B45309', borderDash:[2,2], pointRadius:3, tension:0.3, spanGaps:true },
                    { label: 'EMA', data: data.chart.ema, borderColor:'#CFE1B9', pointRadius:3, tension:0.3, spanGaps:true },
                ]
            },
            options: {
                responsive:true, maintainAspectRatio:false,
                plugins: { legend:{labels:{font:{size:9}}}, tooltip:{mode:'index',intersect:false} },
                scales: { x:{grid:{display:false},ticks:{font:{size:9}}}, y:{grid:{color:'#E7F5DC'},ticks:{font:{size:9}}} }
            }
        });
    }

    // MACD Badge
    const macdBadge = document.getElementById('macd-status-badge');
    if (macdBadge) {
        macdBadge.classList.remove('hidden');
        const isBullish = data.histogram >= 0;
        macdBadge.style.background = isBullish ? '#D1FAE5' : '#FEE2E2';
        macdBadge.style.color      = isBullish ? '#065F46' : '#991B1B';
        macdBadge.textContent = isBullish ? '📈 Bullish' : '📉 Bearish';
    }

    // MACD Chart
    const macdCtx = document.getElementById('macdChart');
    if (macdCtx && data.chart.macd) {
        if (macdChart) macdChart.destroy();
        const macdData   = data.chart.macd.macd   || [];
        const signalData = data.chart.macd.signal || [];
        const histData   = data.chart.macd.histogram || [];
        const histLabels = histData.map((_, i) => `P${i-histData.length+1}`);
        macdChart = new Chart(macdCtx, {
            data: {
                labels: histLabels,
                datasets: [
                    { type:'bar', label:'Histogram', data:histData, backgroundColor: histData.map(v=>v>=0?'rgba(26,59,34,0.6)':'rgba(239,68,68,0.6)'), borderRadius:2 },
                    { type:'line', label:'MACD Line', data:macdData, borderColor:'#1A3B22', pointRadius:2, tension:0.4, fill:false, spanGaps:true },
                    { type:'line', label:'Signal Line', data:signalData, borderColor:'#B45309', borderDash:[3,2], pointRadius:2, tension:0.4, fill:false, spanGaps:true }
                ]
            },
            options: {
                responsive:true, maintainAspectRatio:false,
                plugins:{ legend:{labels:{font:{size:9}}}, tooltip:{mode:'index',intersect:false} },
                scales:{ x:{ticks:{font:{size:9}},grid:{display:false}}, y:{grid:{color:'#E7F5DC'},ticks:{font:{size:9}}} }
            }
        });
    }
});
</script>
@endpush
