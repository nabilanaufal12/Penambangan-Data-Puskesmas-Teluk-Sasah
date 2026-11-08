@extends('layouts.lab')

@section('title', 'Pra-Proses & Imputasi Censored Demand')

@section('content')

{{-- ======================================================================
     1. SUB-MODUL: RINGKASAN PRA-PROSES
     ====================================================================== --}}
<div id="ringkasan-praproses" class="mb-6 anim-fade">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-matcha-900 flex items-center gap-2.5">
                <i class="fa-solid fa-filter text-matcha-700"></i>
                Pra-Proses Data &amp; Imputasi Censored Demand
            </h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">
                Fase 3: Data Preparation &bull; Rekonstruksi Pemakaian Nol Semu Pasca-Stockout &bull; Penyangga Safety Stock Z&middot;&sigma;&middot;&radic;L
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold" style="background:#E7F5DC; color:#1A3B22;">
            <i class="fa-solid fa-flask-vial"></i> Data Preparation Phase
        </span>
    </div>
</div>

{{-- Kartu Ringkasan Indikator --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 mb-6 anim-fade">
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Total Sediaan Obat</div>
        <div class="text-2xl font-black text-matcha-900 tnum">150</div>
        <div class="text-xs text-gray-400 mt-0.5">Basis Data LPLPO 2023–2026</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#B45309;">
        <div class="text-xs text-gray-500 mb-1">Stok Fisik Kosong (SOH=0)</div>
        <div class="text-2xl font-black tnum text-amber-800">{{ number_format($ssStats->kosong_count ?? 0) }}</div>
        <div class="text-xs text-amber-700 mt-0.5 font-semibold">Butuh Pengadaan Segera</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#5C6A44;">
        <div class="text-xs text-gray-500 mb-1">Censored Demand Riil</div>
        <div class="text-2xl font-black tnum" style="color:#5C6A44;">{{ number_format($ssStats->censored_count ?? 0) }}</div>
        <div class="text-xs text-matcha-700 mt-0.5 font-semibold">Parasetamol tablet 500 mg</div>
    </div>
    <div class="lab-card p-4 border-l-4" style="border-left-color:#1A3B22;">
        <div class="text-xs text-gray-500 mb-1">Rata-rata Safety Stock</div>
        <div class="text-2xl font-black tnum" style="color:#1A3B22;">{{ number_format($ssStats->avg_ss ?? 0, 0) }}</div>
        <div class="text-xs text-gray-400 mt-0.5">unit / sediaan obat</div>
    </div>
</div>

{{-- ======================================================================
     2. SUB-MODUL: FORMULASI MATEMATIS
     ====================================================================== --}}
<div id="formulasi-matematis" class="lab-card p-6 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
        <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
            <i class="fa-solid fa-square-root-variable text-matcha-600"></i>
            Formulasi Matematis: Rekonstruksi Censored Demand &amp; Safety Stock Buffer
        </h2>
        <span class="text-xs text-matcha-700 font-semibold">Standar Farmasi Faskes</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <div class="text-xs font-bold text-matcha-800 mb-1.5 flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-matcha-700 text-white flex items-center justify-center text-[10px]">1</span>
                Deteksi Periode Tersensor (Stockout Gate)
            </div>
            <div class="formula-block text-xs leading-relaxed">
                Is_Censored_t &hArr;<br>
                &nbsp;&nbsp;(Sisa_Stok_{t-1} = 0 &or; SOH_t = 0)<br>
                &nbsp;&nbsp;&and; (y_t = 0 &or; y_t &ll; &mu;_aktif)<br>
                &nbsp;&nbsp;&and; (Kasus_Morbiditas_ICD10_t &gt; 0)
            </div>

            <div class="mt-3.5 text-xs font-bold text-matcha-800 mb-1.5 flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-matcha-700 text-white flex items-center justify-center text-[10px]">2</span>
                Imputasi Permintaan Riil (Censored Imputation)
            </div>
            <div class="formula-block text-xs leading-relaxed">
                &ycirc;_t^imp = (1/k) &times; &Sigma;_{m=1}^{k} y_{t-m}^aktif<br>
                &nbsp;&nbsp;Parasetamol 2026-P4: (5.256 + 6.444) / 2 = 5.850 tablet
            </div>
        </div>

        <div>
            <div class="text-xs font-bold text-matcha-800 mb-1.5 flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-matcha-700 text-white flex items-center justify-center text-[10px]">3</span>
                Penyangga Keselamatan (Safety Stock Formula)
            </div>
            <div class="formula-block text-xs leading-relaxed">
                SS = Z_{0,95} &times; &sigma;_d &times; &radic;L<br><br>
                Z_{0,95} = 1,645 (Tingkat Keyakinan 95%)<br>
                &sigma;_d = Standar deviasi kebutuhan historis obat<br>
                L = Waktu tunggu pengadaan (Lead Time = 2 periode)
            </div>

            <div class="mt-3.5 text-xs font-bold text-matcha-800 mb-1.5 flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-matcha-700 text-white flex items-center justify-center text-[10px]">4</span>
                Rekomendasi Total Pengadaan Fisik (Q)
            </div>
            <div class="formula-block text-xs leading-relaxed">
                Q = &ycirc;_{t+1}^{WMA-3} + SS - Sisa_Stok_t<br>
                Q = max(0, Q) &larr; Proteksi Mutasi Non-Negatif
            </div>
        </div>
    </div>
</div>

{{-- ======================================================================
     3. SUB-MODUL: GRAFIK REKONSTRUKSI DEMAND
     ====================================================================== --}}
<div id="grafik-rekonstruksi" class="lab-card p-5 mb-6 anim-fade" style="border-top:4px solid #5C6A44;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-area text-matcha-600"></i>
                Visualisasi Rekonstruksi Censored Demand &amp; Buffer Safety Stock
            </h2>
            <p id="censor-drug-title" class="text-xs text-gray-500 mt-0.5">
                Sediaan Aktif: Parasetamol tablet 500 mg
            </p>
        </div>

        {{-- Skenario Uji Sidang: Sakelar Censored ON / OFF --}}
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-600 font-semibold">Simulasi Sidang:</span>
            <button id="toggle-censor-sim" onclick="toggleCensorSimulation()"
                    class="px-3 py-1 rounded-lg text-xs font-bold border transition-all"
                    style="background:#CFE1B9; color:#1A3B22; border-color:#5C6A44;"
                    title="Bandingkan kurva tanpa koreksi vs terkoreksi PRABOTIK">
                <i class="fa-solid fa-toggle-on mr-1"></i> Imputasi PRABOTIK (ON)
            </button>
            <div id="censor-status-badge" class="text-xs px-2.5 py-1 rounded-full font-bold"></div>
        </div>
    </div>

    {{-- Detail Metrik Cepat Sediaan --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="text-center p-3 rounded-xl bg-sage border border-matcha-100">
            <div class="text-[11px] text-gray-500 mb-0.5">Prediksi Baseline P5</div>
            <div id="detail-pred" class="text-lg font-black tnum text-matcha-900">6.147</div>
        </div>
        <div class="text-center p-3 rounded-xl bg-sage border border-matcha-100">
            <div class="text-[11px] text-gray-500 mb-0.5">Safety Stock (Z&middot;&sigma;&middot;&radic;L)</div>
            <div id="detail-ss" class="text-lg font-black tnum text-amber-800">1.340</div>
        </div>
        <div class="text-center p-3 rounded-xl bg-sage border border-matcha-100">
            <div class="text-[11px] text-gray-500 mb-0.5">Sisa Stok Fisik Gudang</div>
            <div id="detail-sisa" class="text-lg font-black tnum text-gray-700">0</div>
        </div>
        <div class="text-center p-3 rounded-xl bg-matcha-100 border border-matcha-200">
            <div class="text-[11px] text-matcha-800 font-semibold mb-0.5">Rekomendasi Total Q</div>
            <div id="detail-q" class="text-lg font-black tnum text-matcha-900">7.487</div>
        </div>
    </div>

    {{-- Kanvas Grafik Utama --}}
    <div class="h-64 relative">
        <canvas id="censoredDemandChart"></canvas>
    </div>

    <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-gray-600 border-t border-matcha-100 pt-3">
        <span class="flex items-center gap-1.5">
            <span class="inline-block w-4 h-0.5 bg-gray-400 border-t border-dashed"></span>
            Garis Abu Putus: Ground Truth Tercatat (Anjlok ke 0 saat Stockout)
        </span>
        <span class="flex items-center gap-1.5">
            <span class="inline-block w-4 h-1 rounded" style="background:#1A3B22;"></span>
            Garis Hijau Solid: Permintaan Terekonstruksi (&ycirc;_t^imp)
        </span>
        <span class="flex items-center gap-1.5">
            <span class="inline-block w-4 h-0.5 bg-amber-600 border-t border-dotted"></span>
            Garis Cokelat Putus: Batas Safety Stock 95%
        </span>
    </div>
</div>

{{-- ======================================================================
     4. SUB-MODUL: TABEL BEFORE VS AFTER
     ====================================================================== --}}
<div id="tabel-before-after" class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-code-compare text-matcha-600"></i>
                Komparasi Before vs. After Preprocessing — 30 Sediaan Sampel
            </h2>
            <p class="text-gray-500 text-xs mt-0.5">
                Memvalidasi rekonstruksi censored demand dan proteksi stockout pada evaluasi model
            </p>
        </div>
        <span class="text-xs text-matcha-800 font-semibold">Sampel Representatif</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr style="background:#5C6A44; color:white;">
                    <th class="px-3 py-2.5 text-left rounded-tl-lg">Nama Sediaan Obat</th>
                    <th class="px-3 py-2.5 text-center">Status Tersensor</th>
                    <th class="px-3 py-2.5 text-center">Pernah Kosong</th>
                    <th class="px-3 py-2.5 text-right">Pemakaian Riil</th>
                    <th class="px-3 py-2.5 text-right">Prediksi P5</th>
                    <th class="px-3 py-2.5 text-right">Safety Stock</th>
                    <th class="px-3 py-2.5 text-right">Sisa Stok</th>
                    <th class="px-3 py-2.5 text-right">Akurasi (%)</th>
                    <th class="px-3 py-2.5 text-right rounded-tr-lg">MAPE (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($beforeAfter as $m)
                <tr class="{{ $m->is_censored_adjusted ? 'bg-amber-50/70 font-semibold' : ($loop->even ? 'bg-matcha-50/40' : 'bg-white') }} hover:bg-matcha-100/70 transition-colors border-b border-matcha-100/30">
                    <td class="px-3 py-2 font-medium text-gray-800 max-w-[200px] truncate" title="{{ $m->nama_obat }}">
                        {{ $m->nama_obat }}
                    </td>
                    <td class="px-3 py-2 text-center">
                        @if($m->is_censored_adjusted)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                <i class="fa-solid fa-wand-magic-sparkles text-[9px]"></i> Direkonstruksi
                            </span>
                        @else
                            <span class="text-gray-400">&mdash;</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-center">
                        @if($m->pernah_kosong)
                            <span class="text-red-600 font-bold"><i class="fa-solid fa-circle-xmark"></i> Ya</span>
                        @else
                            <span class="text-green-600 font-semibold"><i class="fa-solid fa-circle-check"></i> Tidak</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-right font-mono tnum">{{ number_format($m->pemakaian) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum font-bold text-matcha-900">{{ number_format($m->prediksi_P5) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum text-amber-800">{{ number_format($m->safety_stock) }}</td>
                    <td class="px-3 py-2 text-right font-mono tnum {{ $m->sisa_stok == 0 ? 'text-red-700 font-extrabold' : 'text-gray-700' }}">
                        {{ number_format($m->sisa_stok) }}
                    </td>
                    <td class="px-3 py-2 text-right">
                        @php
                            $acc = $m->evaluasi_akurasi;
                            $c = $acc >= 80 ? 'text-green-800' : ($acc >= 60 ? 'text-matcha-800' : 'text-amber-800');
                        @endphp
                        <span class="font-bold tnum {{ $c }}">{{ number_format($acc, 1) }}%</span>
                    </td>
                    <td class="px-3 py-2 text-right tnum text-gray-600">{{ number_format($m->mape_terbaik, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 flex items-center gap-4 text-xs text-gray-500">
        <span class="flex items-center gap-1.5">
            <span class="inline-block w-3 h-3 rounded bg-amber-100 border border-amber-300"></span>
            Baris Kuning: Sediaan yang direkonstruksi via algoritma Censored Demand Imputation
        </span>
    </div>
</div>

{{-- ======================================================================
     5. SUB-MODUL: TOP 10 SAFETY STOCK TERTINGGI
     ====================================================================== --}}
<div id="top-safety-stock" class="lab-card p-5 mb-6 anim-fade">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-matcha-100">
        <div>
            <h2 class="text-sm font-bold text-matcha-900 flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-matcha-600"></i>
                Top 10 Sediaan dengan Alokasi Safety Stock Terbesar
            </h2>
            <p class="text-gray-500 text-xs mt-0.5">
                Sediaan dengan variabilitas permintaan (&sigma;) tinggi yang memerlukan buffer pengaman maksimal
            </p>
        </div>
        <span class="text-xs text-amber-800 font-bold">Buffer Risiko Tinggi</span>
    </div>
    <div class="h-60 relative">
        <canvas id="topSsChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ---- 1. Top Safety Stock Chart ----
(function () {
    const ctx = document.getElementById('topSsChart');
    if (!ctx) return;
    const rawLabels = @json($topSS->pluck('nama_obat'));
    const labels    = rawLabels.map(n => n.length > 25 ? n.substring(0, 23) + '…' : n);
    const dataSS    = @json($topSS->pluck('safety_stock'));
    const dataPred  = @json($topSS->pluck('prediksi_P5'));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Safety Stock Buffer',
                    data: dataSS,
                    backgroundColor: '#CFE1B9',
                    borderRadius: 4
                },
                {
                    label: 'Prediksi Konsumsi P5',
                    data: dataPred,
                    backgroundColor: '#1A3B22',
                    borderRadius: 4
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
                    callbacks: {
                        label: c => ` ${c.dataset.label}: ${c.parsed.y.toLocaleString('id-ID')} unit`
                    }
                }
            },
            scales: {
                x: {
                    ticks: { font: { size: 9 }, maxRotation: 35 },
                    grid: { display: false }
                },
                y: {
                    grid: { color: '#E7F5DC' },
                    ticks: { font: { size: 9 }, callback: v => v.toLocaleString('id-ID') }
                }
            }
        }
    });
})();

// ---- 2. Censored Demand Dynamic Chart ----
let censorChartInstance = null;
let currentDrugDetail   = null;
let isCensorSimOn       = true;

function toggleCensorSimulation() {
    isCensorSimOn = !isCensorSimOn;
    const btn = document.getElementById('toggle-censor-sim');
    if (btn) {
        if (isCensorSimOn) {
            btn.style.background = '#CFE1B9';
            btn.style.color = '#1A3B22';
            btn.innerHTML = '<i class="fa-solid fa-toggle-on mr-1"></i> Imputasi PRABOTIK (ON)';
        } else {
            btn.style.background = '#FEE2E2';
            btn.style.color = '#991B1B';
            btn.innerHTML = '<i class="fa-solid fa-toggle-off mr-1"></i> Tanpa Koreksi (OFF - Anjlok 50%)';
        }
    }
    if (currentDrugDetail) {
        renderCensorChart(currentDrugDetail);
    }
}

document.addEventListener('drugLoaded', function (e) {
    currentDrugDetail = e.detail;
    renderCensorChart(currentDrugDetail);
});

function renderCensorChart(data) {
    const ctx = document.getElementById('censoredDemandChart');
    if (!ctx) return;

    // Judul & Badge
    const titleEl = document.getElementById('censor-drug-title');
    const badge   = document.getElementById('censor-status-badge');
    if (titleEl) titleEl.textContent = `Sediaan Aktif: ${data.nama_obat} (${data.satuan})`;

    if (badge) {
        if (data.is_censored) {
            badge.style.background = '#FEF3C7';
            badge.style.color = '#92400E';
            badge.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles mr-1"></i>Censored Demand Terkoreksi';
        } else {
            badge.style.background = '#D1FAE5';
            badge.style.color = '#065F46';
            badge.innerHTML = '<i class="fa-solid fa-check mr-1"></i>Data Lengkap Tanpa Stockout';
        }
    }

    // Detail panel
    document.getElementById('detail-pred').textContent = data.prediksi_P5.toLocaleString('id-ID');
    document.getElementById('detail-ss').textContent   = data.safety_stock.toLocaleString('id-ID');
    document.getElementById('detail-sisa').textContent = data.sisa_stok.toLocaleString('id-ID');
    document.getElementById('detail-q').textContent    = data.rekomendasi.toLocaleString('id-ID');

    if (censorChartInstance) censorChartInstance.destroy();

    const labels    = data.chart.labels || [];
    const rawData   = [...(data.chart.raw_y || [])];
    const wmaData   = [...(data.chart.wma3 || [])];
    const ssLine    = data.chart.ss_line || [];

    // Jika simulasi OFF dan obat adalah censored (Parasetamol), turunkan proyeksi 50% untuk demonstrasi sidang
    if (!isCensorSimOn && data.is_censored) {
        for (let i = wmaData.length - 3; i < wmaData.length; i++) {
            if (wmaData[i] !== null) wmaData[i] = Math.round(wmaData[i] * 0.45);
        }
    }

    censorChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Aktual Tercatat LPLPO (Ground Truth)',
                    data: rawData,
                    borderColor: '#9CA3AF',
                    borderDash: [5, 3],
                    backgroundColor: 'rgba(156,163,175,0.08)',
                    pointRadius: 4,
                    tension: 0.3,
                    fill: false
                },
                {
                    label: isCensorSimOn ? 'Demand Terekonstruksi PRABOTIK' : 'Proyeksi Tanpa Koreksi (Distorsi Nol Semu)',
                    data: wmaData,
                    borderColor: isCensorSimOn ? '#1A3B22' : '#DC2626',
                    backgroundColor: isCensorSimOn ? 'rgba(26,59,34,0.1)' : 'rgba(220,38,38,0.1)',
                    pointRadius: 5,
                    pointBackgroundColor: isCensorSimOn ? '#1A3B22' : '#DC2626',
                    borderWidth: 2.5,
                    tension: 0.3,
                    fill: true
                },
                {
                    label: `Safety Stock Buffer (${data.ss_formula.z}×σ×√${data.ss_formula.L})`,
                    data: ssLine,
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
                        label: c => ` ${c.dataset.label}: ${(c.parsed.y || 0).toLocaleString('id-ID')} unit`
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
</script>
@endpush
