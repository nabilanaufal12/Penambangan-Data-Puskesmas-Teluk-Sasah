@extends('layouts.executive')

@section('title', 'Ringkasan Eksekutif & SPM Kemenkes')
@section('page_title', 'Ringkasan Eksekutif & Pengawasan Pimpinan Faskes')

@section('content')
@php
    // Angka turunan untuk tampilan saja (data tetap dari controller)
    $spm        = (float) $kpis['ketersediaan_pct'];
    $spmText    = number_format($spm, 1, ',', '.');
    $spmOk      = $spm >= 80;
    $spmBar     = max(0, min(100, $spm));
    $anggaranJt = number_format($kpis['estimasi_anggaran'] / 1000000, 1, ',', '.');
    $edLossJt   = number_format($kpis['total_ed_loss'] / 1000000, 2, ',', '.');
    $d1 = $topDiseases->get(0);
    $d2 = $topDiseases->get(1);
    $diseaseNames  = $topDiseases->map(fn ($d) => $d->nama_penyakit)->values();
    $diseaseTotals = $topDiseases->map(fn ($d) => (float) $d->total_pasien)->values();
    $top5Share     = round($topDiseases->sum(fn ($d) => (float) $d->persentase), 1);
    $edYears       = $edLossByYear->map(fn ($e) => (string) $e->tahun)->values();
    $edLossMio     = $edLossByYear->map(fn ($e) => round(((float) $e->total_loss) / 1000000, 2))->values();
    $shades        = ['#1A3B22', '#4F5E3A', '#728156', '#98A77C', '#B6C99B'];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7 space-y-6">

    <!-- SAPAAN + AKSI UTAMA -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <p class="text-xs font-bold tracking-[0.14em] uppercase text-matcha-600">Panel Pengawasan &amp; Keputusan Strategis</p>
            <h1 class="mt-1 text-2xl sm:text-[32px] leading-tight font-extrabold tracking-tight text-ink">
                Selamat datang, <span class="text-matcha-600">dr. Bambang Utoyo</span>
            </h1>
            <p class="mt-1.5 text-sm text-slate-500 max-w-3xl leading-relaxed">
                Ringkasan ketersediaan obat pelayanan dasar, efisiensi anggaran pengadaan, kerugian obat kadaluarsa, beban morbiditas SIMPUS, dan disposisi pengesahan LPLPO resmi UPTD Puskesmas Teluk Sasah.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('executive.disposition') }}" class="px-5 py-2.5 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white text-xs font-bold shadow-md shadow-matcha-600/25 transition flex items-center gap-2">
                <i class="fa-solid fa-stamp"></i><span>Tinjau &amp; Sahkan LPLPO</span>
            </a>
            <a href="{{ route('executive.diseases') }}" class="px-5 py-2.5 rounded-full bg-white hover:bg-matcha-50 text-matcha-800 text-xs font-bold shadow-float transition flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-matcha-500"></i><span>Analisis Morbiditas</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <!-- Featured Card: Skor SPM -->
        <div class="relative overflow-hidden rounded-3xl p-5 text-white shadow-float-lg"
             style="background: linear-gradient(145deg, #1A3B22 0%, #2A4A2E 100%);">
            <div class="absolute inset-0 opacity-[0.12] pointer-events-none"
                 style="background-image: radial-gradient(#CFE1B9 1px, transparent 1px); background-size: 14px 14px;"></div>
            <div class="relative flex items-start justify-between">
                <div class="w-11 h-11 rounded-2xl bg-white text-matcha-900 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-matcha-200 text-matcha-900 text-[11px] font-bold">
                    {{ $spmOk ? 'Target terpenuhi' : 'Di bawah target' }}
                </span>
            </div>
            <p class="relative mt-5 text-xs font-semibold text-matcha-200">Skor Capaian SPM &bull; Ketersediaan Obat</p>
            <p class="relative mt-0.5 text-[36px] leading-none font-extrabold tnum">{{ $spmText }}<span class="text-xl font-bold text-matcha-200">%</span></p>
            <div class="relative mt-4">
                <div class="relative h-2 rounded-full bg-white/15 overflow-visible">
                    <div class="h-full rounded-full bg-matcha-200" style="width: {{ $spmBar }}%"></div>
                    <span class="absolute -top-1 h-4 w-0.5 rounded bg-white" style="left: 80%" title="Target 80%"></span>
                </div>
                <p class="mt-2 text-[11px] text-matcha-200">Target Kemenkes: &ge; 80% &bull; {{ $kpis['obat_tersedia'] }} dari {{ $kpis['total_obat'] }} sediaan tersedia</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-float">
            <div class="flex items-start justify-between">
                <div class="w-11 h-11 rounded-2xl bg-matcha-100 text-matcha-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-matcha-100 text-matcha-800 text-[11px] font-bold">SIMPUS</span>
            </div>
            <p class="mt-5 text-xs font-semibold text-slate-500">Kasus Terdiagnosis (10 Besar)</p>
            <p class="mt-0.5 text-[32px] leading-none font-extrabold text-ink tnum">{{ number_format($kpis['total_pasien'], 0, ',', '.') }}</p>
            <p class="mt-4 text-[11px] text-slate-400">Akumulasi 10 diagnosis teratas per bulan</p>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-float">
            <div class="flex items-start justify-between">
                <div class="w-11 h-11 rounded-2xl bg-matcha-100 text-matcha-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-matcha-100 text-matcha-800 text-[11px] font-bold">P5 &bull; 2026</span>
            </div>
            <p class="mt-5 text-xs font-semibold text-slate-500">Usulan Pengadaan P5</p>
            <p class="mt-0.5 text-[32px] leading-none font-extrabold text-ink tnum">Rp {{ $anggaranJt }} <span class="text-sm font-semibold text-slate-400">Jt</span></p>
            <p class="mt-4 text-[11px] text-slate-400">{{ number_format($kpis['total_order_unit'], 0, ',', '.') }} unit sediaan</p>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-float">
            <div class="flex items-start justify-between">
                <div class="w-11 h-11 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-sack-xmark"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 text-[11px] font-bold">Perlu dikendalikan</span>
            </div>
            <p class="mt-5 text-xs font-semibold text-slate-500">Kerugian Obat Rusak / ED</p>
            <p class="mt-0.5 text-[32px] leading-none font-extrabold text-rose-600 tnum">Rp {{ $edLossJt }} <span class="text-sm font-semibold text-slate-400">Jt</span></p>
            <p class="mt-4 text-[11px] text-slate-400">Audit 3 Tahun (APBD/DAK)</p>
        </div>
    </div>

    <!-- BARIS UTAMA -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- Kiri: Disposisi + tren kerugian ED -->
        <div class="lg:col-span-7 space-y-5">

            <div class="bg-white rounded-3xl p-6 shadow-float space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full bg-matcha-100 text-matcha-800 text-[10px] font-extrabold uppercase tracking-wider">Dokumen Otorisasi</span>
                            <span class="text-xs text-slate-400 font-mono">LPLPO-TS-2026-P5</span>
                        </div>
                        <h2 class="text-base font-extrabold text-ink mt-2">Lembar Disposisi Pengesahan Pengadaan Obat P5 (2026)</h2>
                    </div>
                    @if($kpis['is_approved'])
                    <span class="px-3.5 py-1.5 rounded-full bg-matcha-100 text-matcha-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 self-start">
                        <i class="fa-solid fa-stamp"></i><span>Telah Disahkan Digital</span>
                    </span>
                    @else
                    <span class="px-3.5 py-1.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 self-start">
                        <i class="fa-solid fa-clock-rotate-left"></i><span>Menunggu Disposisi Kapus</span>
                    </span>
                    @endif
                </div>

                <!-- 6 alokasi terbesar -->
                <div>
                    <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.12em] mb-2">6 Alokasi Terbesar yang Diajukan Bagian Farmasi</h3>
                    <div class="overflow-x-auto -mx-2">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                    <th class="py-2.5 px-3">Nama Sediaan</th>
                                    <th class="py-2.5 px-3 text-right">Sisa Stok</th>
                                    <th class="py-2.5 px-3 text-right">Rekomendasi</th>
                                    <th class="py-2.5 px-3">Indikasi Utama</th>
                                </tr>
                            </thead>
                            <tbody class="font-medium text-slate-700">
                                @foreach($topProcurement as $tp)
                                <tr class="{{ $loop->odd ? 'bg-matcha-50/70' : '' }}">
                                    <td class="py-2.5 px-3 rounded-l-2xl">
                                        <div class="font-bold text-ink">{{ $tp->nama_obat }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $tp->satuan }}</div>
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-ink tnum">{{ number_format($tp->sisa_stok, 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-extrabold text-matcha-700 tnum">{{ number_format($tp->rekomendasi_total, 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 rounded-r-2xl text-[11px] text-slate-600">
                                        @if(str_contains(strtolower($tp->nama_obat), 'fero'))
                                            Program Bumil &amp; Anemia
                                        @elseif(str_contains(strtolower($tp->nama_obat), 'amlodipin'))
                                            Penyakit Hipertensi (I10)
                                        @elseif(str_contains(strtolower($tp->nama_obat), 'parasetamol'))
                                            Antipiretik ISPA (J00)
                                        @elseif(str_contains(strtolower($tp->nama_obat), 'metformin'))
                                            Diabetes Melitus (E11)
                                        @else
                                            Analgesik / Alergi
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Penandatangan & aksi -->
                <div class="bg-matcha-50 rounded-3xl p-4 sm:p-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 bg-white rounded-2xl">
                            <span class="text-[10px] text-slate-400 block uppercase font-semibold tracking-wider">Petugas Penelaah Farmasi</span>
                            <span class="font-bold text-ink text-sm mt-0.5 block">{{ $kpis['pengelola_nama'] }}</span>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $kpis['pengelola_sip'] }}</span>
                            <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-0.5 rounded-full bg-matcha-100 text-matcha-800 text-[10px] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-matcha-600"></span>Selesai Verifikasi Stok Opname
                            </span>
                        </div>
                        <div class="p-3.5 bg-white rounded-2xl {{ $kpis['is_approved'] ? 'ring-2 ring-matcha-300' : '' }}">
                            <span class="text-[10px] text-slate-400 block uppercase font-semibold tracking-wider">Kepala UPTD Puskesmas Teluk Sasah</span>
                            <span class="font-bold text-ink text-sm mt-0.5 block">{{ $kpis['kapus_nama'] }}</span>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $kpis['kapus_nip'] }}</span>
                            @if($kpis['is_approved'])
                            <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-0.5 rounded-full bg-matcha-600 text-white text-[10px] font-bold">
                                <i class="fa-solid fa-stamp"></i>Telah Disahkan Secara Digital
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Menunggu Otorisasi Kepala Faskes
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-3 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-t border-matcha-200/70">
                        <p class="text-[11px] text-slate-500 leading-snug">
                            Dengan mengesahkan dokumen ini, rencana kebutuhan obat resmi diteruskan ke Dinas Kesehatan Kabupaten Bintan.
                        </p>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('executive.print') }}" target="_blank" rel="noopener" class="flex-1 sm:flex-none px-4 py-2.5 rounded-full bg-white hover:bg-matcha-100 text-matcha-900 text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-print"></i><span>Cetak LPLPO Resmi</span>
                            </a>
                            @if(!$kpis['is_approved'])
                            <form method="POST" action="{{ route('executive.approve') }}" class="flex-1 sm:flex-none">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white text-xs font-bold shadow-md shadow-matcha-600/25 transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-stamp"></i><span>Setujui Usulan P5 (Sahkan)</span>
                                </button>
                            </form>
                            @else
                            <span class="px-4 py-2.5 rounded-full bg-matcha-100 text-matcha-800 font-bold text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-matcha-600"></i><span>Usulan Telah Disahkan</span>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tren kerugian ED -->
            <div class="bg-white rounded-3xl p-6 shadow-float">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-extrabold text-ink text-base">Akuntabilitas Kerugian Obat ED</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Evaluasi efisiensi pengadaan anggaran APBD &amp; DAK Farmasi (Rp juta per tahun)</p>
                    </div>
                    <a href="{{ route('executive.financial') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 whitespace-nowrap mt-1">Buka Audit Rinci &rarr;</a>
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-5 gap-4 items-center">
                    <div class="sm:col-span-3 relative h-[190px]">
                        <canvas id="chartEd" role="img" aria-label="Diagram batang kerugian obat kadaluarsa per tahun dalam jutaan rupiah">
                            <table>
                                <caption>Kerugian ED per tahun (Rp)</caption>
                                @foreach($edLossByYear as $ey)
                                <tr><th scope="row">{{ $ey->tahun }}</th><td>{{ number_format($ey->total_loss, 0, ',', '.') }}</td></tr>
                                @endforeach
                            </table>
                        </canvas>
                    </div>
                    <div class="sm:col-span-2 space-y-2.5">
                        @foreach($edLossByYear as $ey)
                        <div class="p-3 bg-matcha-50 rounded-2xl text-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun {{ $ey->tahun }}</span>
                            <span class="block text-sm font-extrabold text-rose-600 font-mono tnum mt-0.5">Rp {{ number_format($ey->total_loss, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-slate-500">{{ $ey->count }} Berita Acara ED</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan: morbiditas + standar terapi -->
        <div class="lg:col-span-5 space-y-5">

            <div class="bg-white rounded-3xl p-6 shadow-float">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-extrabold text-ink text-base">Profil Beban 5 Penyakit Terbanyak</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi SIMPUS wilayah kerja Teluk Sasah</p>
                    </div>
                    <a href="{{ route('executive.diseases') }}" class="text-[11px] font-bold text-matcha-700 hover:text-matcha-900 whitespace-nowrap mt-1">SIMPUS &rarr;</a>
                </div>

                <div class="mt-4 flex flex-col items-center gap-5">
                    <div class="relative w-[180px] h-[180px] shrink-0">
                        <canvas id="chartPenyakit" role="img" aria-label="Diagram donat lima penyakit terbanyak"></canvas>
                    </div>
                    <ul class="w-full space-y-3">
                        @foreach($topDiseases as $td)
                        <li class="text-xs min-w-0">
                            <div class="flex items-center justify-between gap-3">
                                <span class="flex items-center gap-2 min-w-0 font-bold text-ink">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background: {{ $shades[$loop->index] ?? '#B6C99B' }}"></span>
                                    <span class="px-1.5 py-0.5 rounded bg-matcha-50 text-matcha-800 font-mono text-[10px]">{{ $td->kode_icd }}</span>
                                    <span class="truncate">{{ $td->nama_penyakit }}</span>
                                </span>
                                <span class="font-mono font-bold text-ink tnum shrink-0">{{ number_format($td->total_pasien, 0, ',', '.') }} <span class="text-[10px] text-slate-400">({{ number_format($td->persentase, 1, ',', '.') }}%)</span></span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                @if($d1 && $d2)
                <div class="mt-5 p-4 bg-matcha-50 rounded-2xl">
                    <span class="font-bold text-ink text-xs block mb-0.5">Catatan Epidemiologi Pimpinan</span>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        {{ $d1->nama_penyakit }} ({{ number_format($d1->persentase, 1, ',', '.') }}%) dan {{ $d2->nama_penyakit }} ({{ number_format($d2->persentase, 1, ',', '.') }}%) mendominasi {{ number_format($d1->persentase + $d2->persentase, 1, ',', '.') }}% dari total kasus 10 diagnosis teratas SIMPUS, sehingga kelancaran suplai obat terkait (mis. Parasetamol dan Amlodipin) perlu dijaga.
                    </p>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-float">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-extrabold text-ink text-base">Kepatuhan Standar Terapi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Rasio kesesuaian diagnosis dengan terapi obat</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-matcha-50 text-matcha-700 flex items-center justify-center shrink-0"><i class="fa-solid fa-notes-medical text-xs"></i></div>
                </div>

                <div class="mt-4 space-y-2.5">
                    @foreach($correlations as $c)
                    <div class="p-3 bg-matcha-50 rounded-2xl flex items-center justify-between gap-3 text-xs">
                        <div class="min-w-0">
                            <span class="font-bold text-ink block truncate">{{ $c->nama_obat }}</span>
                            <span class="text-[10px] text-slate-500">{{ $c->nama_penyakit }} ({{ $c->kode_icd }})</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-matcha-100 text-matcha-800 shrink-0">Rasio {{ $c->rasio_resep_per_pasien }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="pt-4 text-center">
                    <a href="{{ route('executive.correlations') }}" class="text-xs font-bold text-matcha-700 hover:text-matcha-900">Lihat Seluruh Analisis Korelasi &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    if (typeof Chart === 'undefined') return;

    var fmt = function (n) { return Number(n).toLocaleString('id-ID'); };
    Chart.defaults.font.family = "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif";
    Chart.defaults.color = '#64748b';

    var tooltipBase = {
        backgroundColor: '#142416',
        titleColor: '#ffffff',
        bodyColor: '#E7F5DC',
        padding: 12,
        cornerRadius: 12,
        displayColors: false,
        titleFont: { weight: '700' }
    };

    // 1) Donat: 5 penyakit terbanyak (satu hue, gelap ke terang menurut peringkat)
    var penyakitNama  = @json($diseaseNames);
    var penyakitTotal = @json($diseaseTotals);
    var shades        = @json($shades);
    var el1 = document.getElementById('chartPenyakit');
    if (el1 && penyakitTotal.length) {
        new Chart(el1, {
            type: 'doughnut',
            data: {
                labels: penyakitNama,
                datasets: [{ data: penyakitTotal, backgroundColor: shades.slice(0, penyakitTotal.length), borderWidth: 0, spacing: 3, borderRadius: 7, hoverOffset: 4 }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: Object.assign({}, tooltipBase, {
                        callbacks: { label: function (c) { return c.label + ': ' + fmt(c.parsed) + ' kasus'; } }
                    })
                }
            },
            plugins: [{
                id: 'centerLabel',
                afterDraw: function (chart) {
                    var a = chart.chartArea, ctx = chart.ctx;
                    var cx = (a.left + a.right) / 2, cy = (a.top + a.bottom) / 2;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#142416';
                    ctx.font = "800 26px 'Plus Jakarta Sans', sans-serif";
                    ctx.fillText('{{ number_format($top5Share, 1, ',', '.') }}%', cx, cy - 7);
                    ctx.fillStyle = '#64748b';
                    ctx.font = "600 10px 'Plus Jakarta Sans', sans-serif";
                    ctx.fillText('dari kasus 10 besar', cx, cy + 15);
                    ctx.restore();
                }
            }]
        });
    }

    // 2) Bar: kerugian ED per tahun (Rp juta)
    var tahun = @json($edYears);
    var rugi  = @json($edLossMio);
    var el2 = document.getElementById('chartEd');
    if (el2 && rugi.length) {
        new Chart(el2, {
            type: 'bar',
            data: {
                labels: tahun,
                datasets: [{
                    data: rugi,
                    backgroundColor: '#728156',
                    hoverBackgroundColor: '#1A3B22',
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 44
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: Object.assign({}, tooltipBase, {
                        callbacks: { label: function (c) { return 'Rp ' + c.parsed.y.toLocaleString('id-ID', { minimumFractionDigits: 2 }) + ' juta'; } }
                    })
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: { beginAtZero: true, grid: { color: '#EEF2EA' }, border: { display: false }, ticks: { callback: function (v) { return fmt(v); } } }
                }
            }
        });
    }
})();
</script>
@endpush
