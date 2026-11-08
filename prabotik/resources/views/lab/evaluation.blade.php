@extends('layouts.lab')

@section('title', 'Korelasi ICD-10 & Evaluasi Model')

@section('content')
{{-- ===== HEADER ===== --}}
<div class="mb-6 anim-fade">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-microscope text-matcha-700"></i>
                Korelasi Klinis ICD-10 & Evaluasi Model
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Fase 5: Evaluation — Pearson r · Spearman ρ · MAPE · RMSE · Distribusi Residual ε_t
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold" style="background:#CFE1B9; color:#1A3B22;">
            <i class="fa-solid fa-microscope"></i> Evaluation Phase
        </span>
    </div>
</div>

{{-- ===== KARTU STATISTIK DISTRIBUSI AKURASI ===== --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6 anim-fade">
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Akurasi Excellent (≥90%)</div>
        <div class="text-2xl font-bold tnum" style="color:#1A3B22;">{{ number_format($accuracyDist->tier_excellent ?? 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">obat</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#5C6A44;">
        <div class="text-xs text-gray-500 mb-1">Akurasi Baik (70–90%)</div>
        <div class="text-2xl font-bold tnum" style="color:#5C6A44;">{{ number_format($accuracyDist->tier_good ?? 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">obat</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#B45309;">
        <div class="text-xs text-gray-500 mb-1">Akurasi Sedang (50–70%)</div>
        <div class="text-2xl font-bold tnum" style="color:#B45309;">{{ number_format($accuracyDist->tier_fair ?? 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">obat</div>
    </div>
    <div class="lab-card p-4 border-l-4 border-l-red-500">
        <div class="text-xs text-gray-500 mb-1">Akurasi Rendah (&lt;50%)</div>
        <div class="text-2xl font-bold tnum text-red-600">{{ number_format($accuracyDist->tier_poor ?? 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">obat</div>
    </div>
</div>

{{-- ===== GRID: Heatmap Korelasi + Histogram Residual ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">

    {{-- Heatmap Korelasi ICD-10 --}}
    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-grid-3 text-matcha-600"></i>
            Heatmap Korelasi Klinis ICD-10 — Pearson r & Spearman ρ
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr style="background:#1A3B22; color:white;">
                        <th class="px-2 py-2 text-left rounded-tl">Penyakit (ICD-10)</th>
                        <th class="px-2 py-2 text-center">Obat Standar</th>
                        <th class="px-2 py-2 text-center">Pearson r</th>
                        <th class="px-2 py-2 text-center">Spearman ρ</th>
                        <th class="px-2 py-2 text-right rounded-tr">Dosis/Pasien</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($correlations as $c)
                    @php
                        $r = abs($c->pearson_r);
                        $rColor = $r >= 0.7 ? '#1A3B22' : ($r >= 0.4 ? '#5C6A44' : '#9CA3AF');
                        $rBg    = $r >= 0.7 ? '#D1FAE5' : ($r >= 0.4 ? '#E7F5DC' : '#F3F4F6');
                        $pct    = min(100, round($r * 100));
                    @endphp
                    <tr class="{{ $loop->even ? 'bg-matcha-50' : 'bg-white' }} hover:bg-matcha-100 transition-colors">
                        <td class="px-2 py-2">
                            <div class="font-semibold" style="color:#1A3B22;">{{ $c->kode_icd }}</div>
                            <div class="text-gray-500 text-xs truncate max-w-[130px]" title="{{ $c->nama_penyakit }}">{{ $c->nama_penyakit }}</div>
                        </td>
                        <td class="px-2 py-2 text-center text-gray-600 max-w-[100px]">
                            <div class="truncate" title="{{ $c->nama_obat_standar }}">{{ $c->nama_obat_standar }}</div>
                        </td>
                        <td class="px-2 py-2 text-center">
                            <div class="inline-flex flex-col items-center gap-1">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold tnum" style="background:{{ $rBg }}; color:{{ $rColor }};">
                                    {{ number_format($c->pearson_r, 4) }}
                                </span>
                                <div class="w-14 h-1 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $rColor }};"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-2 text-center">
                            @php $rhoAbs = abs($c->spearman_rho); $rhoColor = $rhoAbs >= 0.7 ? '#1A3B22' : ($rhoAbs >= 0.4 ? '#5C6A44' : '#9CA3AF'); @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold tnum" style="background:#F0FDF4; color:{{ $rhoColor }};">
                                {{ number_format($c->spearman_rho, 4) }}
                            </span>
                        </td>
                        <td class="px-2 py-2 text-right font-mono tnum font-semibold text-matcha-800">
                            {{ number_format($c->rasio_dosis_per_pasien, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3 formula-block text-xs">
            Interpretasi: |r|≥0.7 = Korelasi Kuat · 0.4–0.7 = Sedang · &lt;0.4 = Lemah<br>
            H₀ ditolak → Hubungan linier signifikan antara kasus ICD-10 dan konsumsi obat
        </div>
    </div>

    {{-- Histogram Distribusi Akurasi --}}
    <div class="lab-card p-5 anim-fade">
        <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-column text-matcha-600"></i>
            Histogram Distribusi Akurasi Model — 150 Obat
        </h2>
        <div class="h-48">
            <canvas id="accuracyHistChart"></canvas>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-2 text-xs text-center">
            <div class="p-2 rounded-lg" style="background:#D1FAE5;">
                <div class="font-bold text-green-800 tnum">{{ number_format($accuracyDist->avg_acc ?? 0, 1) }}%</div>
                <div class="text-green-700">Rata-rata</div>
            </div>
            <div class="p-2 rounded-lg" style="background:#FEF9C3;">
                <div class="font-bold text-yellow-800 tnum">{{ number_format($accuracyDist->min_acc ?? 0, 1) }}%</div>
                <div class="text-yellow-700">Minimum</div>
            </div>
            <div class="p-2 rounded-lg" style="background:#E7F5DC;">
                <div class="font-bold tnum" style="color:#1A3B22;">{{ number_format($accuracyDist->max_acc ?? 0, 1) }}%</div>
                <div style="color:#5C6A44;">Maksimum</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== RUMUS EVALUASI ===== --}}
<div class="lab-card p-5 mb-6 anim-fade">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-square-root-variable text-matcha-600"></i>
        Formulasi Metrik Evaluasi Multi-Dimensi
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @php
            $metrics = [
                ['name'=>'MAPE (Mean Abs. % Error)','formula'=>"MAPE = (1/n)·Σ|y_t - ŷ_t| / y_t · 100%\n\nSensitif terhadap nilai aktual kecil"],
                ['name'=>'RMSE (Root Mean Sq. Error)','formula'=>"RMSE = √[(1/n)·Σ(y_t - ŷ_t)²]\n\nMenghukum galat besar lebih berat"],
                ['name'=>'BSA (Bounded Symmetric Acc.)','formula'=>"BSA = max(0, 100 - |y−ŷ|/max(y,ŷ,1)·100)\n\nAkurasi simetris 0–100% (digunakan)"],
                ['name'=>'Pearson Correlation (r)','formula'=>"r = Σ(x_i-x̄)(y_i-ȳ) / √[Σ(x_i-x̄)²·Σ(y_i-ȳ)²]\n\nLinearitas ICD-10 ↔ Pemakaian Obat"],
                ['name'=>'Spearman Correlation (ρ)','formula'=>"ρ = 1 - 6·Σd_i² / (n(n²-1))\n\nKorelasi rank (tidak asumsi normalitas)"],
                ['name'=>'Residual (ε_t)','formula'=>"ε_t = y_t - ŷ_t\n\nDistribusi residual normal ↔ model valid\nBias sistematik = masalah model"],
            ];
        @endphp
        @foreach($metrics as $m)
        <div class="rounded-xl overflow-hidden border border-matcha-100">
            <div class="px-3 py-2" style="background:#1A3B22;">
                <span class="text-white font-semibold text-xs">{{ $m['name'] }}</span>
            </div>
            <div class="formula-block rounded-none border-0 text-xs whitespace-pre-line leading-relaxed">{{ $m['formula'] }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== GRAFIK RESIDUAL (dari Drug Switcher) ===== --}}
<div class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
            <i class="fa-solid fa-wave-square text-matcha-600"></i>
            Diagnostik Residual ε_t = y_t − ŷ_t
            <span class="text-xs font-normal text-gray-400 ml-1">(Pilih obat di atas)</span>
        </h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="h-48 relative">
            <canvas id="residualChart"></canvas>
            <div id="residual-placeholder" class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-gray-200 text-xs">
                    <i class="fa-solid fa-wave-square text-3xl mb-2"></i>
                    <div>Distribusi residual akan muncul di sini</div>
                </div>
            </div>
        </div>
        <div id="eval-metrics-panel" class="hidden grid grid-cols-2 gap-3 content-start">
            <div class="p-3 rounded-lg text-center" style="background:#F3F8EE;">
                <div class="text-xs text-gray-500 mb-1">Akurasi BSA</div>
                <div id="eval-acc" class="text-xl font-bold tnum" style="color:#1A3B22;">—</div>
            </div>
            <div class="p-3 rounded-lg text-center" style="background:#F3F8EE;">
                <div class="text-xs text-gray-500 mb-1">MAPE</div>
                <div id="eval-mape" class="text-xl font-bold tnum" style="color:#5C6A44;">—</div>
            </div>
            <div class="p-3 rounded-lg text-center col-span-2" style="background:#E7F5DC;">
                <div class="text-xs text-gray-500 mb-1">Model Terbaik</div>
                <div id="eval-model" class="text-base font-bold" style="color:#1A3B22;">—</div>
            </div>
            <div class="col-span-2">
                <div id="eval-interpretation" class="text-xs text-gray-600 p-3 rounded-lg" style="background:#F3F8EE; border-left:3px solid #5C6A44;">
                    Pilih sediaan obat untuk melihat interpretasi evaluasi model
                </div>
            </div>
        </div>
        <div id="eval-placeholder-panel" class="hidden md:flex items-center justify-center text-gray-200 text-xs text-center">
            <div>
                <i class="fa-solid fa-vials text-3xl mb-2"></i>
                <div>Panel evaluasi akan muncul setelah obat dipilih</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== LEADERBOARD BENCHMARK ===== --}}
<div class="lab-card p-5 mb-6 anim-fade">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-ranking-star text-amber-500"></i>
        Leaderboard Model — Benchmark Komparatif WAPE
    </h2>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#1A3B22; color:white;">
                    <th class="px-3 py-2 text-left rounded-tl-lg">#</th>
                    <th class="px-3 py-2 text-left">Nama Model</th>
                    <th class="px-3 py-2 text-center">Kategori</th>
                    <th class="px-3 py-2 text-right">WAPE</th>
                    <th class="px-3 py-2 text-center">Bias %</th>
                    <th class="px-3 py-2 text-right">Stabilitas (SD)</th>
                    <th class="px-3 py-2 text-left rounded-tr-lg">Interpretasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($benchmarks as $i => $b)
                @php
                    $isWinner = $i === 0;
                    $isGood   = $b->wape <= 0.65;
                @endphp
                <tr class="{{ $isWinner ? 'bg-matcha-100 font-semibold' : ($loop->even ? 'bg-matcha-50' : 'bg-white') }} hover:bg-matcha-100 transition-colors">
                    <td class="px-3 py-2">
                        @if($isWinner)
                            <span class="text-amber-500 font-bold"><i class="fa-solid fa-trophy"></i></span>
                        @else
                            <span class="text-gray-400">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 {{ $isWinner ? 'text-matcha-900 font-bold' : 'text-gray-700' }}">{{ $b->model_name }}</td>
                    <td class="px-3 py-2 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs" style="background:#E7F5DC; color:#3E4A2F;">{{ $b->kategori }}</span>
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum font-bold {{ $isGood ? '' : 'text-red-500' }}" style="{{ $isGood ? 'color:#1A3B22;' : '' }}">
                        {{ number_format($b->wape * 100, 1) }}%
                    </td>
                    <td class="px-3 py-2 text-center font-mono tnum">
                        @php $biasPos = str_contains($b->bias_percentage, '+'); @endphp
                        <span class="px-1.5 py-0.5 rounded text-xs {{ $biasPos ? 'text-green-700 bg-green-50' : 'text-red-700 bg-red-50' }}">
                            {{ $b->bias_percentage }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum">{{ number_format($b->stabilitas_sd, 3) }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $b->interpretasi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ===== TABEL PREDIKSI KASUS P5 (dari Clinical Correlations) ===== --}}
<div class="lab-card p-5 mb-6 anim-fade">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-stethoscope text-matcha-600"></i>
        Prediksi Kasus & Kebutuhan Obat P5 — Berbasis Korelasi ICD-10
    </h2>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#5C6A44; color:white;">
                    <th class="px-3 py-2 text-left rounded-tl-lg">ICD-10</th>
                    <th class="px-3 py-2 text-left">Penyakit</th>
                    <th class="px-3 py-2 text-left">Obat Standar</th>
                    <th class="px-3 py-2 text-right">Pearson r</th>
                    <th class="px-3 py-2 text-right">Prediksi Kasus P5</th>
                    <th class="px-3 py-2 text-right rounded-tr-lg">Prediksi Kebutuhan P5</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topDiseasesPred as $d)
                <tr class="{{ $loop->even ? 'bg-matcha-50' : 'bg-white' }} hover:bg-matcha-100 transition-colors">
                    <td class="px-3 py-2 font-mono font-semibold" style="color:#5C6A44;">{{ $d->kode_icd }}</td>
                    <td class="px-3 py-2 font-medium text-gray-800">{{ $d->nama_penyakit }}</td>
                    <td class="px-3 py-2 text-gray-600 max-w-[150px] truncate" title="{{ $d->nama_obat_standar }}">{{ $d->nama_obat_standar }}</td>
                    <td class="px-3 py-2 text-right">
                        @php $r = abs($d->pearson_r); $rc = $r>=0.7?'#1A3B22':($r>=0.4?'#5C6A44':'#9CA3AF'); @endphp
                        <span class="font-bold tnum" style="color:{{ $rc }};">{{ number_format($d->pearson_r, 4) }}</span>
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum font-semibold text-matcha-800">
                        {{ number_format($d->prediksi_kasus_P5) }}
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum font-bold" style="color:#1A3B22;">
                        {{ number_format($d->prediksi_kebutuhan_obat_P5) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ===== MODE SIDANG — RINGKASAN EKSEKUTIF ===== --}}
<div class="lab-card p-5 mb-6 anim-fade" style="border:2px solid #1A3B22;">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#1A3B22;">
            <i class="fa-solid fa-graduation-cap text-white text-lg"></i>
        </div>
        <div>
            <h2 class="text-base font-bold" style="color:#1A3B22;">Mode Sidang — Ringkasan Pembuktian CRISP-DM</h2>
            <div class="text-xs text-gray-500">Kelompok 23 · INF11121 · UMRAH · 2401020069</div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
            <div class="font-semibold text-matcha-800 mb-2">✅ Capaian Utama Proyek</div>
            <ul class="space-y-1.5 text-gray-700 text-xs">
                <li class="flex gap-2"><span style="color:#1A3B22;">▸</span> Capaian SPM Kemenkes: <strong>91,3%</strong> (target ≥80%)</li>
                <li class="flex gap-2"><span style="color:#1A3B22;">▸</span> Total data diproses: <strong>31.125 kunjungan</strong> SIMPUS + 150 obat LPLPO</li>
                <li class="flex gap-2"><span style="color:#1A3B22;">▸</span> Kerugian ED teridentifikasi: <strong>Rp 43.348.626</strong> / 138 item</li>
                <li class="flex gap-2"><span style="color:#1A3B22;">▸</span> Censored demand: <strong>{{ $accuracyDist->avg_acc ? number_format($ssStats->censored_count ?? 0) : '—' }}</strong> obat diimputasi</li>
                <li class="flex gap-2"><span style="color:#1A3B22;">▸</span> Rata-rata akurasi BSA: <strong>{{ number_format($accuracyDist->avg_acc ?? 0, 1) }}%</strong></li>
            </ul>
        </div>
        <div>
            <div class="font-semibold text-matcha-800 mb-2">📊 Validasi Metodologi</div>
            <ul class="space-y-1.5 text-gray-700 text-xs">
                <li class="flex gap-2"><span style="color:#5C6A44;">▸</span> Model utama: <strong>WMA-3</strong> (w=[3,2,1], bias +4,3%)</li>
                <li class="flex gap-2"><span style="color:#5C6A44;">▸</span> Korelasi terkuat: J00↔Parasetamol <strong>r=0,5851</strong></li>
                <li class="flex gap-2"><span style="color:#5C6A44;">▸</span> Safety stock: formula <strong>Z×σ×√L</strong> (Z=1.645, CI 95%)</li>
                <li class="flex gap-2"><span style="color:#5C6A44;">▸</span> MACD lensa dwibulanan: EMA(2,4,2)</li>
                <li class="flex gap-2"><span style="color:#5C6A44;">▸</span> DB: SQLite WAL mode, latensi &lt;35ms/query</li>
            </ul>
        </div>
    </div>
    <div class="mt-4 flex gap-3 flex-wrap">
        <a href="{{ route('lab.eda') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold text-white transition-all hover:opacity-90" style="background:#1A3B22;">
            <i class="fa-solid fa-diagram-project"></i> Pipeline & EDA
        </a>
        <a href="{{ route('lab.preprocess') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold text-white transition-all hover:opacity-90" style="background:#5C6A44;">
            <i class="fa-solid fa-filter"></i> Pra-Proses
        </a>
        <a href="{{ route('lab.modeling') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold text-white transition-all hover:opacity-90" style="background:#728156;">
            <i class="fa-solid fa-chart-line"></i> Arena Model
        </a>
        <a href="{{ route('pharmacy.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-all hover:opacity-90" style="background:#CFE1B9; color:#1A3B22;">
            <i class="fa-solid fa-pills"></i> Portal Farmasi (Hasil Deployment)
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- Accuracy Histogram ----
(function () {
    const ctx = document.getElementById('accuracyHistChart');
    if (!ctx) return;
    const bins  = @json($accuracyBins);
    const order = ['0-10%','10-20%','20-30%','30-40%','40-50%','50-60%','60-70%','70-80%','80-90%','90-100%'];
    const dataMap = {};
    bins.forEach(b => dataMap[b.bin_label] = b.count);
    const counts = order.map(k => dataMap[k] || 0);
    const colors = order.map(k => {
        const pct = parseInt(k);
        return pct >= 80 ? '#1A3B22' : (pct >= 60 ? '#5C6A44' : (pct >= 40 ? '#CFE1B9' : '#FCA5A5'));
    });
    new Chart(ctx, {
        type: 'bar',
        data: { labels: order, datasets: [{ label: 'Jumlah Obat', data: counts, backgroundColor: colors, borderRadius: 4 }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false },
                tooltip: { callbacks: { label: c => ` ${c.parsed.y} obat` } } },
            scales: {
                x: { ticks: { font: { size: 9 }, maxRotation: 35 }, grid: { display: false } },
                y: { grid: { color: '#E7F5DC' }, ticks: { font: { size: 9 }, stepSize: 1 } }
            }
        }
    });
})();

// ---- Residual Chart (from drug switcher) ----
let residualChart = null;

document.addEventListener('drugLoaded', function (e) {
    const data = e.detail;

    // Hide placeholder
    const ph = document.getElementById('residual-placeholder');
    if (ph) ph.style.display = 'none';

    // Show eval metrics panel
    const panel = document.getElementById('eval-metrics-panel');
    const eph   = document.getElementById('eval-placeholder-panel');
    if (panel) { panel.classList.remove('hidden'); panel.classList.add('grid'); }
    if (eph)   eph.classList.add('hidden');

    document.getElementById('eval-acc').textContent   = data.evaluasi_akurasi + '%';
    document.getElementById('eval-mape').textContent  = data.mape + '%';
    document.getElementById('eval-model').textContent = data.model_terbaik;

    const acc = data.evaluasi_akurasi;
    let interp = '';
    if (acc >= 90)      interp = '✅ Excellent — Model sangat akurat, layak deployment produksi';
    else if (acc >= 70) interp = '✅ Baik — Model cukup akurat, bisa digunakan dengan pengawasan';
    else if (acc >= 50) interp = '⚠️ Sedang — Pertimbangkan penyesuaian parameter model';
    else                interp = '❌ Rendah — Tingginya variansi demand, cek censored demand';
    document.getElementById('eval-interpretation').textContent = interp;

    // Build residual bar chart from chart data
    const ctx = document.getElementById('residualChart');
    if (!ctx) return;

    const actual = (data.chart.actual || []).filter(v => v !== null);
    const wma3   = (data.chart.wma3  || []).filter(v => v !== null);
    const n = Math.min(actual.length, wma3.length);
    const residuals = [];
    const rLabels   = [];
    for (let i = 0; i < n; i++) {
        residuals.push(parseFloat((actual[i] - wma3[i]).toFixed(1)));
        rLabels.push(`P${i - n + 1}`);
    }

    if (residualChart) residualChart.destroy();
    residualChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: rLabels,
            datasets: [{
                label: 'Residual ε_t = y_t − ŷ_t',
                data: residuals,
                backgroundColor: residuals.map(v => v >= 0 ? 'rgba(26,59,34,0.65)' : 'rgba(239,68,68,0.6)'),
                borderRadius: 3
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { labels: { font: { size: 9 } } },
                tooltip: { callbacks: { label: c => ` ε = ${c.parsed.y.toLocaleString('id-ID')}` } }
            },
            scales: {
                x: { ticks: { font: { size: 9 } }, grid: { display: false } },
                y: { grid: { color: '#E7F5DC' }, ticks: { font: { size: 9 } },
                    title: { display: true, text: 'Residual (unit)', font: { size: 9 } } }
            }
        }
    });
});
</script>
@endpush
