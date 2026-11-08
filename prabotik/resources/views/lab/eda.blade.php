@extends('layouts.lab')

@section('title', 'Pipeline CRISP-DM & EDA')

@section('content')
{{-- ===== HEADER HALAMAN ===== --}}
<div class="mb-6 anim-fade">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-matcha-700"></i>
                Pipeline CRISP-DM & Eksplorasi Data
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Fase 1–2: Business Understanding & Data Understanding — 150 Obat · 31.125 Kunjungan SIMPUS · 138 BA Obat ED
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold" style="background:#CFE1B9; color:#1A3B22;">
            <i class="fa-solid fa-flask-vial"></i> EDA Phase
        </span>
    </div>
</div>

{{-- ===== KARTU STATISTIK UTAMA ===== --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6 anim-fade">
    @php
        $cards = [
            ['icon'=>'fa-capsules','label'=>'Total Sediaan Obat','value'=>number_format($totalMedicines),'unit'=>'Obat LPLPO','color'=>'#1A3B22'],
            ['icon'=>'fa-users','label'=>'Total Kunjungan SIMPUS','value'=>number_format($totalVisits),'unit'=>'Rawat Jalan','color'=>'#5C6A44'],
            ['icon'=>'fa-triangle-exclamation','label'=>'Kerugian Obat ED','value'=>'Rp '.number_format($totalEdLoss,0,',','.'),'unit'=>$totalEdItems.' Item BA','color'=>'#B45309'],
            ['icon'=>'fa-bandage','label'=>'Obat Stok Nol','value'=>number_format($stockout),'unit'=>'Butuh Pengadaan','color'=>'#991B1B'],
            ['icon'=>'fa-wand-magic-sparkles','label'=>'Censored Demand','value'=>number_format($censored),'unit'=>'Diimputasi','color'=>'#5C6A44'],
            ['icon'=>'fa-bullseye','label'=>'Rata-rata Akurasi','value'=>number_format($avgAccuracy,1).'%','unit'=>'Semua Model','color'=>'#1A3B22'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="lab-card p-4 flex flex-col gap-1">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center mb-1" style="background:{{ $card['color'] }}15;">
            <i class="fa-solid {{ $card['icon'] }} text-sm" style="color:{{ $card['color'] }};"></i>
        </div>
        <div class="text-lg font-bold tnum" style="color:{{ $card['color'] }};">{{ $card['value'] }}</div>
        <div class="text-xs text-gray-500">{{ $card['unit'] }}</div>
        <div class="text-xs font-medium text-gray-700">{{ $card['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- ===== PIPELINE CRISP-DM INTERAKTIF ===== --}}
<div class="lab-card p-6 mb-6 anim-fade">
    <h2 class="text-base font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-route text-matcha-600"></i>
        Alur Pipeline CRISP-DM End-to-End
    </h2>
    <div class="flex flex-col md:flex-row items-stretch gap-2 overflow-x-auto pb-2">
        @php
            $phases = [
                ['num'=>1,'label'=>'Business\nUnderstanding','icon'=>'fa-bullseye','color'=>'#1A3B22','desc'=>'Target SPM ≥80%\nAktual: 91,3%','link'=>null],
                ['num'=>2,'label'=>'Data\nUnderstanding','icon'=>'fa-database','color'=>'#5C6A44','desc'=>'150 Obat\n31.125 Visit','link'=>null],
                ['num'=>3,'label'=>'Data\nPreparation','icon'=>'fa-filter','color'=>'#728156','desc'=>'Censored Demand\nSafety Stock','link'=>route('lab.preprocess')],
                ['num'=>4,'label'=>'Modeling','icon'=>'fa-chart-line','color'=>'#5C6A44','desc'=>'WMA-3 vs SMA\nvs SES vs EMA','link'=>route('lab.modeling')],
                ['num'=>5,'label'=>'Evaluation','icon'=>'fa-microscope','color'=>'#1A3B22','desc'=>'MAPE·RMSE\nAkurasi BSA','link'=>route('lab.evaluation')],
                ['num'=>6,'label'=>'Deployment','icon'=>'fa-rocket','color'=>'#3E4A2F','desc'=>'SQLite WAL\nSinkron /farmasi','link'=>null],
            ];
        @endphp
        @foreach($phases as $i => $phase)
        @if($i > 0)
        <div class="hidden md:flex items-center text-matcha-300 shrink-0">
            <i class="fa-solid fa-chevron-right text-lg"></i>
        </div>
        @endif
        <div class="flex-1 min-w-[110px] text-center {{ $phase['link'] ? 'cursor-pointer hover:scale-105' : '' }} transition-transform"
             {{ $phase['link'] ? 'onclick=window.location.href=\''.$phase['link'].'\'' : '' }}>
            <div class="rounded-xl p-3 flex flex-col items-center gap-2" style="background:{{ $phase['color'] }}; color:white;">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold" style="background:rgba(255,255,255,0.2);">
                    {{ $phase['num'] }}
                </div>
                <i class="fa-solid {{ $phase['icon'] }} text-lg"></i>
                <div class="text-xs font-semibold text-center leading-tight whitespace-pre-line">{{ $phase['label'] }}</div>
            </div>
            <div class="mt-2 text-xs text-gray-500 text-center whitespace-pre-line leading-tight">{{ $phase['desc'] }}</div>
            @if($phase['link'])
            <div class="mt-1 text-xs font-semibold" style="color:#5C6A44;">→ Lihat Detail</div>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- ===== GRID: Distribusi Model + Top Penyakit ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Distribusi Model Terbaik --}}
    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-matcha-600"></i>
            Distribusi Model Terbaik — 150 Obat
        </h2>
        <div class="h-56 relative">
            <canvas id="modelDistChart"></canvas>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-1">
            @foreach($modelDist as $m)
            <div class="flex items-center gap-2 text-xs">
                <div class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#{{ ['1A3B22','5C6A44','728156','3E4A2F','CFE1B9','B6C99B'][($loop->index) % 6] }}"></div>
                <span class="text-gray-600">{{ $m->model_terbaik }}</span>
                <span class="font-bold text-matcha-800 ml-auto">{{ $m->jumlah }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Top 10 Penyakit --}}
    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-stethoscope text-matcha-600"></i>
            Top 10 Penyakit SIMPUS (ICD-10) — Total Kunjungan
        </h2>
        <div class="h-56 relative">
            <canvas id="topDiseasesChart"></canvas>
        </div>
    </div>
</div>

{{-- ===== GRID: Distribusi Prioritas + Kerugian ED ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Distribusi Prioritas --}}
    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-layer-group text-matcha-600"></i>
            Distribusi Prioritas Kategori Obat
        </h2>
        <div class="space-y-2">
            @foreach($prioritasDist as $p)
            @php $pct = round(($p->jumlah / $totalMedicines) * 100, 1); @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-medium text-gray-700">{{ $p->prioritas_kategori }}</span>
                    <span class="font-bold text-matcha-800">{{ $p->jumlah }} <span class="text-gray-400">({{ $pct }}%)</span></span>
                </div>
                <div class="h-2 bg-matcha-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all" style="width:{{ $pct }}%; background:#1A3B22;"></div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4 formula-block">
            Pareto ABC-VEN: Prioritas 1 (Vital Fast-Moving)<br>
            Prioritas 2 (Essential Moderate) → Prioritas 3 (Non-Essential)
        </div>
    </div>

    {{-- Kerugian ED per Sumber --}}
    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-money-bill-trend-up text-amber-600"></i>
            Audit Kerugian Obat Kadaluarsa per Sumber Dana
        </h2>
        <div class="space-y-3">
            @php $totalEd = $edBySumber->sum('total') ?: 1; @endphp
            @foreach($edBySumber as $e)
            @php $pct = round(($e->total / $totalEd) * 100, 1); @endphp
            <div class="flex items-center gap-3">
                <div class="w-28 shrink-0 text-xs font-medium text-gray-700 truncate" title="{{ $e->sumber_anggaran }}">
                    {{ $e->sumber_anggaran }}
                </div>
                <div class="flex-1 h-6 bg-amber-50 rounded-lg overflow-hidden border border-amber-100 relative">
                    <div class="h-full rounded-lg" style="width:{{ $pct }}%; background:#B45309;"></div>
                    <span class="absolute inset-0 flex items-center px-2 text-xs font-semibold text-amber-900">
                        Rp {{ number_format($e->total,0,',','.') }}
                    </span>
                </div>
                <div class="text-xs text-gray-400 w-10 text-right shrink-0">{{ $e->items }} item</div>
            </div>
            @endforeach
        </div>
        <div class="mt-4 p-3 rounded-lg text-xs" style="background:#FEF3C7; border:1px solid #FCD34D; color:#78350F;">
            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
            <strong>Total Kerugian APBD & DAK:</strong>
            Rp {{ number_format($totalEdLoss, 0, ',', '.') }} dari {{ number_format($totalEdItems) }} item BA
        </div>
    </div>
</div>

{{-- ===== TABEL TOP 10 PENYAKIT ===== --}}
<div class="lab-card p-5 anim-fade mb-6">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-table-list text-matcha-600"></i>
        Tabel Eksplorasi Top 10 Penyakit ICD-10 (Data Understanding)
    </h2>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#1A3B22; color:white;">
                    <th class="px-3 py-2 text-left rounded-tl-lg">#</th>
                    <th class="px-3 py-2 text-left">Kode ICD-10</th>
                    <th class="px-3 py-2 text-left">Nama Penyakit</th>
                    <th class="px-3 py-2 text-right">Total Kunjungan</th>
                    <th class="px-3 py-2 text-right rounded-tr-lg">Distribusi (%)</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = $topDiseases->sum('grand_total') ?: 1; @endphp
                @foreach($topDiseases as $i => $d)
                <tr class="{{ $loop->even ? 'bg-matcha-50' : 'bg-white' }} hover:bg-matcha-100 transition-colors">
                    <td class="px-3 py-2 font-bold text-matcha-700">{{ $i + 1 }}</td>
                    <td class="px-3 py-2 font-mono font-semibold" style="color:#5C6A44;">{{ $d->kode_icd }}</td>
                    <td class="px-3 py-2 text-gray-700">{{ $d->nama_penyakit }}</td>
                    <td class="px-3 py-2 text-right font-bold tnum text-matcha-900">{{ number_format($d->grand_total) }}</td>
                    <td class="px-3 py-2 text-right">
                        @php $pct = round(($d->grand_total / $grandTotal) * 100, 1); @endphp
                        <div class="flex items-center justify-end gap-2">
                            <div class="w-16 h-1.5 bg-matcha-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="width:{{ min(100,$pct*3) }}%; background:#1A3B22;"></div>
                            </div>
                            <span class="font-semibold tnum">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 formula-block">
        Sumber: SIMPUS Puskesmas Teluk Sasah 2023–2024 · 450 record · 10 kode ICD-10
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- Distribusi Model Pie Chart ----
(function () {
    const ctx = document.getElementById('modelDistChart');
    if (!ctx) return;
    const labels = @json($modelDist->pluck('model_terbaik'));
    const data   = @json($modelDist->pluck('jumlah'));
    const colors = ['#1A3B22','#5C6A44','#728156','#3E4A2F','#CFE1B9','#B6C99B'];
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{ data: data, backgroundColor: colors, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { font: { size: 10, family: 'Plus Jakarta Sans' }, boxWidth: 10, padding: 8 } },
                tooltip: { callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.parsed} obat` } }
            },
            cutout: '62%'
        }
    });
})();

// ---- Top Diseases Horizontal Bar ----
(function () {
    const ctx = document.getElementById('topDiseasesChart');
    if (!ctx) return;
    const labels = @json($topDiseases->pluck('nama_penyakit')->map(fn($n) => strlen($n) > 30 ? substr($n, 0, 28).'…' : $n));
    const data   = @json($topDiseases->pluck('grand_total'));
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Kunjungan',
                data: data,
                backgroundColor: data.map((_, i) => i === 0 ? '#1A3B22' : i < 3 ? '#5C6A44' : '#CFE1B9'),
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => ` ${ctx.parsed.x.toLocaleString('id-ID')} pasien` } }
            },
            scales: {
                x: { grid: { color: '#E7F5DC' }, ticks: { font: { size: 9 } } },
                y: { ticks: { font: { size: 9 } }, grid: { display: false } }
            }
        }
    });
})();
</script>
@endpush
