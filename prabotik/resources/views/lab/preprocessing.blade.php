@extends('layouts.lab')

@section('title', 'Pra-Proses & Imputasi Censored Demand')

@section('content')
{{-- ===== HEADER ===== --}}
<div class="mb-6 anim-fade">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-filter text-matcha-700"></i>
                Pra-Proses Data & Imputasi Censored Demand
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Fase 3: Data Preparation — Rekonstruksi Permintaan Tersensor & Kalkulasi Safety Stock (Z·σ·√L)
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold" style="background:#E7F5DC; color:#1A3B22;">
            <i class="fa-solid fa-filter"></i> Preprocessing Phase
        </span>
    </div>
</div>

{{-- ===== KARTU STATISTIK ===== --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6 anim-fade">
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Total Obat</div>
        <div class="text-2xl font-bold text-matcha-900 tnum">150</div>
        <div class="text-xs text-gray-400 mt-1">Sediaan LPLPO</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#B45309;">
        <div class="text-xs text-gray-500 mb-1">Stok Kosong (SOH=0)</div>
        <div class="text-2xl font-bold tnum" style="color:#B45309;">{{ number_format($ssStats->kosong_count ?? 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">Butuh Pengadaan Segera</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#5C6A44;">
        <div class="text-xs text-gray-500 mb-1">Censored & Diimputasi</div>
        <div class="text-2xl font-bold tnum" style="color:#5C6A44;">{{ number_format($ssStats->censored_count ?? 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">Permintaan Tersensor</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Rata-rata Safety Stock</div>
        <div class="text-2xl font-bold tnum" style="color:#1A3B22;">{{ number_format($ssStats->avg_ss ?? 0, 0) }}</div>
        <div class="text-xs text-gray-400 mt-1">unit/obat</div>
    </div>
</div>

{{-- ===== RUMUS MATEMATIS CENSORED DEMAND ===== --}}
<div class="lab-card p-6 mb-6 anim-fade">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-square-root-variable text-matcha-600"></i>
        Formulasi Matematis: Censored Demand Imputation & Safety Stock
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <div class="text-xs font-semibold text-matcha-700 mb-2">① Deteksi Periode Tersensor</div>
            <div class="formula-block text-xs leading-relaxed">
                Is_Censored_t ⟺<br>
                &nbsp;&nbsp;(SOH_{t-1} = 0 ∨ Sisa_Stok_t = 0)<br>
                &nbsp;&nbsp;∧ (y_t ≪ μ_aktif)<br>
                &nbsp;&nbsp;∧ (Kasus_ICD10_t > 0)
            </div>
            <div class="mt-3 text-xs font-semibold text-matcha-700 mb-2">② Imputasi Permintaan Riil</div>
            <div class="formula-block text-xs leading-relaxed">
                ŷ_t^imp = (1/k) · Σ y_{t-m}^aktif<br>
                &nbsp;&nbsp;(rata-rata k periode aktif sebelumnya)
            </div>
        </div>
        <div>
            <div class="text-xs font-semibold text-matcha-700 mb-2">③ Safety Stock (Penyangga Stok)</div>
            <div class="formula-block text-xs leading-relaxed">
                SS = Z_(0,95) × σ_d × √L<br><br>
                Z_(0,95) = 1,645 (interval kepercayaan 95%)<br>
                σ_d = standar deviasi demand historis<br>
                L = lead time pengadaan (periode)
            </div>
            <div class="mt-3 text-xs font-semibold text-matcha-700 mb-2">④ Rekomendasi Total Pengadaan</div>
            <div class="formula-block text-xs leading-relaxed">
                Q = Prediksi_P5 + SS - Sisa_Stok<br>
                Q = max(0, Q)  ← tidak boleh negatif
            </div>
        </div>
    </div>
</div>

{{-- ===== CHART: Visualisasi Censored Demand (dari Drug Switcher) ===== --}}
<div class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
            <i class="fa-solid fa-chart-area text-matcha-600"></i>
            Visualisasi Rekonstruksi Permintaan Tersensor
            <span class="text-xs font-normal text-gray-400 ml-1">(Pilih obat di atas untuk memuat grafik)</span>
        </h2>
        <div id="censor-status-badge" class="hidden text-xs px-2 py-1 rounded-full font-semibold"></div>
    </div>
    <div class="h-64 relative">
        <canvas id="censoredDemandChart"></canvas>
        <div id="censor-chart-placeholder" class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-gray-300">
                <i class="fa-solid fa-magnifying-glass text-4xl mb-2"></i>
                <div class="text-sm">Gunakan kotak pencarian di atas untuk memilih sediaan obat</div>
            </div>
        </div>
    </div>
    {{-- Detail panel --}}
    <div id="drug-detail-panel" class="hidden mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="text-center p-3 rounded-lg" style="background:#F3F8EE;">
            <div class="text-xs text-gray-500 mb-1">Prediksi P5</div>
            <div id="detail-pred" class="text-lg font-bold tnum text-matcha-900">—</div>
        </div>
        <div class="text-center p-3 rounded-lg" style="background:#F3F8EE;">
            <div class="text-xs text-gray-500 mb-1">Safety Stock</div>
            <div id="detail-ss" class="text-lg font-bold tnum text-matcha-900">—</div>
        </div>
        <div class="text-center p-3 rounded-lg" style="background:#F3F8EE;">
            <div class="text-xs text-gray-500 mb-1">Sisa Stok</div>
            <div id="detail-sisa" class="text-lg font-bold tnum text-matcha-900">—</div>
        </div>
        <div class="text-center p-3 rounded-lg" style="background:#F3F8EE;">
            <div class="text-xs text-gray-500 mb-1">Rekomendasi Q</div>
            <div id="detail-q" class="text-lg font-bold tnum text-matcha-900">—</div>
        </div>
    </div>
</div>

{{-- ===== BEFORE / AFTER TABEL ===== --}}
<div class="lab-card p-5 mb-6 anim-fade">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-code-compare text-matcha-600"></i>
        Komparasi Before vs. After Preprocessing — 30 Obat Sampel
    </h2>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#5C6A44; color:white;">
                    <th class="px-3 py-2 text-left rounded-tl-lg">Nama Obat</th>
                    <th class="px-3 py-2 text-center">Tersensor</th>
                    <th class="px-3 py-2 text-center">Pernah Kosong</th>
                    <th class="px-3 py-2 text-right">Pemakaian LPLPO</th>
                    <th class="px-3 py-2 text-right">Prediksi P5</th>
                    <th class="px-3 py-2 text-right">Safety Stock</th>
                    <th class="px-3 py-2 text-right">Sisa Stok</th>
                    <th class="px-3 py-2 text-right">Akurasi (%)</th>
                    <th class="px-3 py-2 text-right rounded-tr-lg">MAPE (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($beforeAfter as $m)
                <tr class="{{ $m->is_censored_adjusted ? 'bg-yellow-50' : ($loop->even ? 'bg-matcha-50' : 'bg-white') }} hover:bg-matcha-100 transition-colors">
                    <td class="px-3 py-2 font-medium text-gray-800 max-w-[180px] truncate" title="{{ $m->nama_obat }}">
                        {{ $m->nama_obat }}
                    </td>
                    <td class="px-3 py-2 text-center">
                        @if($m->is_censored_adjusted)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold" style="background:#FEF9C3; color:#854D0E;">
                                <i class="fa-solid fa-wand-magic-sparkles text-[9px]"></i> Ya
                            </span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-center">
                        @if($m->pernah_kosong)
                            <span class="text-red-500 font-bold"><i class="fa-solid fa-xmark"></i></span>
                        @else
                            <span class="text-matcha-500"><i class="fa-solid fa-check"></i></span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum">{{ number_format($m->pemakaian) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum font-semibold text-matcha-800">{{ number_format($m->prediksi_P5) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum" style="color:#5C6A44;">{{ number_format($m->safety_stock) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum {{ $m->sisa_stok == 0 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                        {{ number_format($m->sisa_stok) }}
                    </td>
                    <td class="px-3 py-2 text-right">
                        @php $acc = $m->evaluasi_akurasi; $accColor = $acc >= 80 ? '#1A3B22' : ($acc >= 60 ? '#5C6A44' : '#B45309'); @endphp
                        <span class="font-bold tnum" style="color:{{ $accColor }};">{{ number_format($acc, 1) }}</span>
                    </td>
                    <td class="px-3 py-2 text-right tnum text-gray-600">{{ number_format($m->mape_terbaik, 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-2 flex gap-4 text-xs text-gray-500">
        <span class="inline-flex items-center gap-1.5">
            <span class="inline-block w-3 h-3 rounded bg-yellow-50 border border-yellow-300"></span>
            Baris kuning = Censored demand diimputasi
        </span>
        <span class="inline-flex items-center gap-1.5">
            <i class="fa-solid fa-xmark text-red-500"></i> Pernah stockout
        </span>
    </div>
</div>

{{-- ===== TOP 10 SAFETY STOCK TERTINGGI ===== --}}
<div class="lab-card p-5 anim-fade mb-6">
    <h2 class="text-sm font-bold text-matcha-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-shield-halved text-matcha-600"></i>
        Top 10 Obat — Safety Stock Tertinggi (Volatilitas Pemakaian Tinggi)
    </h2>
    <div class="h-56">
        <canvas id="topSsChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- Top Safety Stock Chart ----
(function () {
    const ctx = document.getElementById('topSsChart');
    if (!ctx) return;
    const labels = @json($topSS->pluck('nama_obat')->map(fn($n) => strlen($n) > 25 ? substr($n,0,23).'…' : $n));
    const dataSS = @json($topSS->pluck('safety_stock'));
    const dataPred = @json($topSS->pluck('prediksi_P5'));
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Safety Stock', data: dataSS, backgroundColor: '#CFE1B9', borderRadius: 3 },
                { label: 'Prediksi P5', data: dataPred, backgroundColor: '#1A3B22', borderRadius: 3 }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { font: { size: 10 } } },
                tooltip: { callbacks: { label: c => ` ${c.dataset.label}: ${c.parsed.y.toLocaleString('id-ID')}` } } },
            scales: {
                x: { ticks: { font: { size: 9 }, maxRotation: 35 }, grid: { display: false } },
                y: { grid: { color: '#E7F5DC' }, ticks: { font: { size: 9 } } }
            }
        }
    });
})();

// ---- Censored Demand Chart (from drug switcher) ----
let censorChart = null;

document.addEventListener('drugLoaded', function (e) {
    const data = e.detail;
    const ctx  = document.getElementById('censoredDemandChart');
    if (!ctx) return;

    document.getElementById('censor-chart-placeholder').style.display = 'none';

    // Status badge
    const badge = document.getElementById('censor-status-badge');
    badge.classList.remove('hidden');
    if (data.is_censored) {
        badge.style.background = '#FEF9C3'; badge.style.color = '#854D0E';
        badge.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles mr-1"></i>Censored & Diimputasi';
    } else {
        badge.style.background = '#D1FAE5'; badge.style.color = '#065F46';
        badge.innerHTML = '<i class="fa-solid fa-check mr-1"></i>Data Lengkap (Tidak Tersensor)';
    }

    // Detail panel
    const panel = document.getElementById('drug-detail-panel');
    panel.classList.remove('hidden');
    document.getElementById('detail-pred').textContent = data.prediksi_P5.toLocaleString('id-ID');
    document.getElementById('detail-ss').textContent   = data.safety_stock.toLocaleString('id-ID');
    document.getElementById('detail-sisa').textContent = data.sisa_stok.toLocaleString('id-ID');
    document.getElementById('detail-q').textContent    = data.rekomendasi.toLocaleString('id-ID');

    if (censorChart) censorChart.destroy();

    censorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.chart.labels,
            datasets: [
                {
                    label: 'Aktual LPLPO (Ground Truth)',
                    data: data.chart.actual,
                    borderColor: '#9CA3AF',
                    borderDash: [5, 3],
                    backgroundColor: 'rgba(156,163,175,0.1)',
                    pointRadius: 4,
                    tension: 0.3,
                    spanGaps: true
                },
                {
                    label: 'Demand Terekonstruksi (WMA-3)',
                    data: data.chart.wma3,
                    borderColor: '#1A3B22',
                    backgroundColor: 'rgba(26,59,34,0.08)',
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#1A3B22',
                    tension: 0.3,
                    spanGaps: true
                },
                {
                    label: `Safety Stock (SS = ${data.ss_formula.z}×σ×√${data.ss_formula.L})`,
                    data: data.chart.ss_line,
                    borderColor: '#B45309',
                    borderDash: [3, 3],
                    backgroundColor: 'transparent',
                    pointRadius: 0,
                    tension: 0
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { labels: { font: { size: 10 } } },
                tooltip: { mode: 'index', intersect: false, callbacks: { label: c => ` ${c.dataset.label}: ${(c.parsed.y || 0).toLocaleString('id-ID')}` } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { grid: { color: '#E7F5DC' }, ticks: { font: { size: 10 } } }
            }
        }
    });
});
</script>
@endpush
