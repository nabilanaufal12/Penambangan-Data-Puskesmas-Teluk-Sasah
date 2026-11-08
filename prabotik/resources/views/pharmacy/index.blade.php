@extends('layouts.pharmacy')

@section('title', 'Beranda Gudang & Logistik Obat')
@section('page_title', 'Operasional Gudang & Persediaan Obat Fisik')

@section('content')
@php
    // Angka turunan untuk tampilan saja (data tetap dari controller)
    $aktif   = max(1, (int) $stats['total_obat_aktif']);
    $aman    = (int) $stats['stok_aman_count'];
    $kritis  = (int) $stats['stok_kritis_count'];
    $kosong  = (int) $stats['stok_kosong_count'];
    $pctAman = (int) round($aman / $aktif * 100);
    $unitP5  = number_format($stats['total_rekomendasi_unit'], 0, ',', '.');
    $chartNames = $procurementPreview->pluck('nama_obat')->values();
    $chartUnits = $procurementPreview->map(fn ($m) => (float) $m->rekomendasi_total)->values();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7 space-y-6">

    <!-- SAPAAN + AKSI UTAMA -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <p class="text-xs font-bold tracking-[0.14em] uppercase text-matcha-600">Instalasi Farmasi &bull; UPTD Puskesmas Teluk Sasah</p>
            <h1 class="mt-1 text-2xl sm:text-[32px] leading-tight font-extrabold tracking-tight text-ink">
                Halo, <span class="text-matcha-600">Apt. Siti Rahmawati</span>
            </h1>
            <p class="mt-1.5 text-sm text-slate-500 max-w-2xl leading-relaxed">
                Pantau sisa stok fisik di gudang obat, deteksi sediaan yang menipis di bawah cadangan pengaman, dan susun usulan lembar LPLPO dwibulanan Periode 5 (2026).
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('pharmacy.entry') }}" class="px-5 py-2.5 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white text-xs font-bold shadow-md shadow-matcha-600/25 transition flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i><span>Pusat Entri Data</span>
            </a>
            <a href="{{ route('pharmacy.print') }}" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-full bg-white hover:bg-matcha-50 text-matcha-800 text-xs font-bold shadow-float transition flex items-center gap-2">
                <i class="fa-solid fa-print text-matcha-500"></i><span>Cetak Lembar LPLPO</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI -->
    <div id="kpi-section" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 scroll-mt-28">

        <!-- Featured Card (aksen hijau tua) -->
        <div class="relative overflow-hidden rounded-3xl p-5 text-white shadow-float-lg"
             style="background: linear-gradient(145deg, #1A3B22 0%, #2A4A2E 100%);">
            <div class="absolute inset-0 opacity-[0.12] pointer-events-none"
                 style="background-image: radial-gradient(#CFE1B9 1px, transparent 1px); background-size: 14px 14px;"></div>
            <div class="relative flex items-start justify-between">
                <div class="w-11 h-11 rounded-2xl bg-white text-matcha-900 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-cart-flatbed"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-matcha-200 text-matcha-900 text-[11px] font-bold">Sep &ndash; Okt 2026</span>
            </div>
            <p class="relative mt-5 text-xs font-semibold text-matcha-200">Total Usulan Order P5</p>
            <p class="relative mt-0.5 text-[32px] leading-none font-extrabold tnum">{{ $unitP5 }} <span class="text-sm font-semibold text-matcha-200">Unit</span></p>
            <a href="{{ route('pharmacy.procurement') }}" class="relative mt-4 inline-flex items-center gap-1.5 text-[11px] font-bold text-white/90 hover:text-white">
                Tinjau rencana pengadaan <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-float">
            <div class="flex items-start justify-between">
                <div class="w-11 h-11 rounded-2xl bg-matcha-100 text-matcha-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-capsules"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-matcha-100 text-matcha-800 text-[11px] font-bold">Formularium</span>
            </div>
            <p class="mt-5 text-xs font-semibold text-slate-500">Total Sediaan Aktif</p>
            <p class="mt-0.5 text-[32px] leading-none font-extrabold text-ink tnum">{{ $stats['total_obat_aktif'] }} <span class="text-sm font-semibold text-slate-400">Item</span></p>
            <p class="mt-4 text-[11px] text-slate-400">Tercatat di Formularium</p>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-float">
            <div class="flex items-start justify-between">
                <div class="w-11 h-11 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-700 text-[11px] font-bold">Wajib diusulkan</span>
            </div>
            <p class="mt-5 text-xs font-semibold text-slate-500">Stok Kosong Fisik</p>
            <p class="mt-0.5 text-[32px] leading-none font-extrabold text-rose-600 tnum">{{ $stats['stok_kosong_count'] }} <span class="text-sm font-semibold text-slate-400">Item</span></p>
            <p class="mt-4 text-[11px] text-slate-400">Wajib Masuk Usulan Segera</p>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-float">
            <div class="flex items-start justify-between">
                <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold">Di Bawah Cadangan Aman</span>
            </div>
            <p class="mt-5 text-xs font-semibold text-slate-500">Stok Kritis</p>
            <p class="mt-0.5 text-[32px] leading-none font-extrabold text-amber-600 tnum">{{ $stats['stok_kritis_count'] }} <span class="text-sm font-semibold text-slate-400">Item</span></p>
            <p class="mt-4 text-[11px] text-slate-400">Menembus Buffer Cadangan</p>
        </div>
    </div>

    <!-- STATUS PENGAJUAN KE KEPALA PUSKESMAS -->
    @if($stats['is_approved'])
    <div class="bg-matcha-100 rounded-3xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-matcha-600 text-white flex items-center justify-center shrink-0 text-lg">
                <i class="fa-solid fa-stamp"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-matcha-900 text-sm">Usulan Rencana Pengadaan P5 (2026) telah disahkan Kepala Puskesmas</h3>
                <p class="text-xs text-matcha-800/80 mt-0.5 leading-relaxed">
                    Dokumen usulan LPLPO telah disetujui secara digital oleh <strong>dr. Bambang Utoyo, M.M.</strong> dan siap dicetak untuk dikirim ke Instalasi Farmasi Dinas Kesehatan Kabupaten Bintan.
                </p>
            </div>
        </div>
        <a href="{{ route('pharmacy.print') }}" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white text-xs font-bold transition shrink-0 text-center">
            Cetak Lembar Pengesahan &rarr;
        </a>
    </div>
    @else
    <div class="bg-white rounded-3xl p-5 shadow-float flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 text-lg">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-ink text-sm">Status Usulan Pengadaan P5: menunggu otorisasi Kepala Puskesmas</h3>
                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                    Kalkulasi usulan order sebanyak <strong class="text-ink">{{ $unitP5 }} unit obat</strong> telah selesai disusun dan siap ditelaah oleh Kepala UPTD Puskesmas pada portal eksekutif.
                </p>
            </div>
        </div>
        <a href="{{ route('pharmacy.procurement') }}" class="px-5 py-2.5 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white text-xs font-bold transition shrink-0 text-center">
            Review Usulan Order &rarr;
        </a>
    </div>
    @endif

    <!-- GRAFIK: USULAN TERBESAR + KONDISI STOK -->
    <div id="grafik-section" class="grid grid-cols-1 lg:grid-cols-12 gap-5 scroll-mt-28">

        <div id="usulan-chart-section" class="lg:col-span-8 bg-white rounded-3xl p-6 shadow-float scroll-mt-28">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="font-extrabold text-ink text-base">Usulan Order Terbesar P5</h3>
                    <p class="text-xs text-slate-500 mt-0.5">8 sediaan dengan rekomendasi pengadaan tertinggi (unit)</p>
                </div>
                <a href="{{ route('pharmacy.procurement') }}" class="w-9 h-9 rounded-full bg-matcha-50 hover:bg-matcha-100 text-matcha-700 flex items-center justify-center transition shrink-0" aria-label="Buka rencana pengadaan">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                </a>
            </div>
            <div class="relative mt-4 h-[300px]">
                <canvas id="chartUsulan" role="img" aria-label="Diagram batang horizontal usulan order P5 untuk 8 sediaan terbesar">
                    <table>
                        <caption>Usulan order P5 (unit)</caption>
                        @foreach($procurementPreview as $pp)
                        <tr><th scope="row">{{ $pp->nama_obat }}</th><td>{{ number_format($pp->rekomendasi_total, 0, ',', '.') }}</td></tr>
                        @endforeach
                    </table>
                </canvas>
            </div>
        </div>

        <div id="kondisi-stok-section" class="lg:col-span-4 bg-white rounded-3xl p-6 shadow-float flex flex-col scroll-mt-28">
            <div>
                <h3 class="font-extrabold text-ink text-base">Kondisi Stok Gudang</h3>
                <p class="text-xs text-slate-500 mt-0.5">Sebaran {{ $stats['total_obat_aktif'] }} sediaan aktif</p>
            </div>
            <div class="relative mt-3 h-[190px]">
                <canvas id="chartStok" role="img" aria-label="Diagram donat kondisi stok: {{ $aman }} aman, {{ $kritis }} kritis, {{ $kosong }} kosong"></canvas>
            </div>
            <ul class="mt-4 space-y-2.5 text-xs">
                <li class="flex items-center justify-between">
                    <span class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full" style="background:#728156"></span>Aman (&gt; SS)</span>
                    <span class="font-bold text-ink tnum">{{ $aman }} item</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full" style="background:#F59E0B"></span>Kritis (&le; SS)</span>
                    <span class="font-bold text-ink tnum">{{ $kritis }} item</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="flex items-center gap-2 text-slate-600"><span class="w-2.5 h-2.5 rounded-full" style="background:#E11D48"></span>Kosong</span>
                    <span class="font-bold text-ink tnum">{{ $kosong }} item</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- TABEL + PANEL KANAN -->
    <div id="restock-section" class="grid grid-cols-1 lg:grid-cols-12 gap-5 scroll-mt-28">

        <!-- Kiri: Prioritas restock + MACD -->
        <div class="lg:col-span-8 space-y-5">

            <div class="bg-white rounded-3xl shadow-float overflow-hidden">
                <div class="px-6 pt-6 pb-4 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-extrabold text-ink text-base">Prioritas Restock Fisik Gudang</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Sediaan dengan stok gudang di bawah batas cadangan aman</p>
                    </div>
                    <a href="{{ route('pharmacy.procurement') }}" class="text-[11px] font-bold text-matcha-700 hover:text-matcha-900 whitespace-nowrap mt-1">
                        Lihat {{ $stats['total_obat_aktif'] }} Obat &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto px-3 pb-2">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                <th class="py-3 px-3">Nama Sediaan</th>
                                <th class="py-3 px-3 text-right">Sisa Fisik</th>
                                <th class="py-3 px-3 text-right">Cadangan Aman</th>
                                <th class="py-3 px-3 text-right">Usulan Order</th>
                                <th class="py-3 px-3">Kondisi Stok</th>
                            </tr>
                        </thead>
                        <tbody class="font-medium text-slate-700">
                            @foreach($criticalMedicines as $cm)
                            @php
                                $ssVal  = max(1, (float) $cm->safety_stock);
                                $barPct = (int) min(100, round(((float) $cm->sisa_stok) / $ssVal * 100));
                            @endphp
                            <tr class="{{ $loop->odd ? 'bg-matcha-50/70' : '' }} hover:bg-matcha-100/60 transition">
                                <td class="py-3 px-3 rounded-l-2xl">
                                    <div class="font-bold text-ink">{{ $cm->nama_obat }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $cm->satuan }} &bull; {{ $cm->model_terbaik }}</div>
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-extrabold tnum {{ $cm->sisa_stok == 0 ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ number_format($cm->sisa_stok, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-slate-500 tnum">
                                    {{ number_format($cm->safety_stock, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-extrabold text-matcha-700 tnum">
                                    +{{ number_format($cm->rekomendasi_total, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 rounded-r-2xl min-w-[130px]">
                                    @if($cm->sisa_stok == 0)
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-rose-700"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Stok Kosong</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-700"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Buffer Kritis</span>
                                    @endif
                                    <div class="mt-1.5 h-1.5 w-full rounded-full bg-matcha-100 overflow-hidden" title="Sisa fisik terhadap Safety Stock">
                                        <div class="h-full rounded-full {{ $cm->sisa_stok == 0 ? 'bg-rose-400' : 'bg-amber-400' }}" style="width: {{ $barPct }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-slate-500">
                    <span>Rumus standar Kemenkes RI: <strong class="text-ink">Order = (Prediksi + SS) &minus; Sisa Stok</strong></span>
                    <a href="{{ route('pharmacy.print') }}" target="_blank" rel="noopener" class="font-bold text-matcha-700 hover:text-matcha-900">Cetak LPLPO &rarr;</a>
                </div>
            </div>

            <!-- Deteksi Tren Momentum MACD -->
            <div id="tren-section" class="bg-white rounded-3xl p-6 shadow-float scroll-mt-28">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-extrabold text-ink text-base">Tren Kebutuhan Obat (Lonjakan Resep)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Obat yang sedang mengalami peningkatan pemakaian di ruang pelayanan</p>
                    </div>
                    <a href="{{ route('pharmacy.macd') }}" class="text-[11px] font-bold text-matcha-700 hover:text-matcha-900 whitespace-nowrap mt-1">Selengkapnya &rarr;</a>
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($bullishMedicines as $bm)
                    <div class="p-3.5 bg-matcha-50 rounded-2xl flex items-center justify-between gap-3 text-xs">
                        <div class="min-w-0">
                            <span class="font-bold text-ink block truncate">{{ $bm->nama_obat }}</span>
                            <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full bg-matcha-100 text-matcha-800 text-[10px] font-bold">
                                <i class="fa-solid fa-arrow-trend-up text-[9px]"></i>{{ $bm->momentum_status }}
                            </span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[10px] text-slate-400 block">Usulan Order</span>
                            <span class="font-mono font-extrabold text-ink tnum">{{ number_format($bm->rekomendasi_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kanan: aksi cepat + audit ED -->
        <div class="lg:col-span-4 space-y-5">

            <div class="bg-white rounded-3xl p-6 shadow-float">
                <h3 class="font-extrabold text-ink text-base flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-matcha-600 text-sm"></i><span>Tindakan Operasional</span>
                </h3>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a href="{{ route('pharmacy.entry', ['tab' => 'lplpo']) }}" class="p-3.5 rounded-2xl bg-matcha-50 hover:bg-matcha-100 transition block">
                        <div class="w-9 h-9 rounded-xl bg-matcha-600 text-white flex items-center justify-center text-sm mb-2.5"><i class="fa-solid fa-pen-to-square"></i></div>
                        <div class="font-bold text-xs text-ink">Update Stok LPLPO</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Catat stok &amp; pemakaian fisik</div>
                    </a>
                    <a href="{{ route('pharmacy.entry', ['tab' => 'obat']) }}" class="p-3.5 rounded-2xl bg-matcha-50 hover:bg-matcha-100 transition block">
                        <div class="w-9 h-9 rounded-xl bg-matcha-600 text-white flex items-center justify-center text-sm mb-2.5"><i class="fa-solid fa-pills"></i></div>
                        <div class="font-bold text-xs text-ink">Daftar Obat Baru</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Tambah ke formularium</div>
                    </a>
                    <a href="{{ route('pharmacy.entry', ['tab' => 'kadaluarsa']) }}" class="p-3.5 rounded-2xl bg-matcha-50 hover:bg-matcha-100 transition block">
                        <div class="w-9 h-9 rounded-xl bg-matcha-600 text-white flex items-center justify-center text-sm mb-2.5"><i class="fa-solid fa-calendar-xmark"></i></div>
                        <div class="font-bold text-xs text-ink">Catat Berita Acara ED</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Obat rusak/kadaluarsa</div>
                    </a>
                    <a href="{{ route('pharmacy.print') }}" target="_blank" rel="noopener" class="p-3.5 rounded-2xl bg-matcha-50 hover:bg-matcha-100 transition block">
                        <div class="w-9 h-9 rounded-xl bg-matcha-600 text-white flex items-center justify-center text-sm mb-2.5"><i class="fa-solid fa-print"></i></div>
                        <div class="font-bold text-xs text-ink">Cetak Lembar Kerja</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Format Dinkes Bintan</div>
                    </a>
                </div>
            </div>

            <!-- Audit Obat Kadaluarsa (FEFO) -->
            <div class="bg-white rounded-3xl p-6 shadow-float">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-extrabold text-ink text-base">Audit Obat Kadaluarsa</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Riwayat ED terkini (FEFO)</p>
                    </div>
                    <a href="{{ route('pharmacy.audit') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 whitespace-nowrap mt-1">Detail Audit &rarr;</a>
                </div>

                <div class="mt-4 space-y-2.5">
                    @foreach($recentExpired as $re)
                    <div class="p-3 bg-matcha-50 rounded-2xl flex items-center justify-between gap-3 text-xs">
                        <div class="min-w-0">
                            <span class="font-bold text-ink block truncate">{{ $re->nama_obat }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $re->tahun }} &bull; {{ number_format($re->jumlah_kadaluarsa, 0, ',', '.') }} {{ $re->satuan }}</span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-[10px] text-slate-400 block">Kerugian</span>
                            <span class="font-mono font-extrabold text-rose-600 tnum">Rp {{ number_format($re->sub_total_kerugian, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-matcha-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Total Akumulasi Kerugian</span>
                    <span class="font-extrabold text-rose-600 font-mono tnum">Rp {{ number_format($stats['total_ed_loss'], 0, ',', '.') }}</span>
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

    // 1) Bar horizontal: usulan order terbesar
    var namaObat = @json($chartNames);
    var unitOrder = @json($chartUnits);
    var maxIdx = unitOrder.indexOf(Math.max.apply(null, unitOrder));
    var el1 = document.getElementById('chartUsulan');
    if (el1 && unitOrder.length) {
        new Chart(el1, {
            type: 'bar',
            data: {
                labels: namaObat,
                datasets: [{
                    data: unitOrder,
                    backgroundColor: unitOrder.map(function (_, i) { return i === maxIdx ? '#728156' : '#B6C99B'; }),
                    hoverBackgroundColor: '#5C6A44',
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 16
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                layout: { padding: { right: 8 } },
                plugins: {
                    legend: { display: false },
                    tooltip: Object.assign({}, tooltipBase, {
                        callbacks: { label: function (c) { return fmt(c.parsed.x) + ' unit'; } }
                    })
                },
                scales: {
                    x: { grid: { color: '#EEF2EA', drawBorder: false }, border: { display: false }, ticks: { callback: function (v) { return fmt(v); } } },
                    y: {
                        grid: { display: false }, border: { display: false },
                        ticks: { callback: function (v) { var s = this.getLabelForValue(v); return s.length > 26 ? s.slice(0, 25) + '…' : s; } }
                    }
                }
            }
        });
    }

    // 2) Donat: kondisi stok (label persen di tengah)
    var stok = [{{ $aman }}, {{ $kritis }}, {{ $kosong }}];
    var el2 = document.getElementById('chartStok');
    if (el2) {
        new Chart(el2, {
            type: 'doughnut',
            data: {
                labels: ['Aman (> SS)', 'Kritis (≤ SS)', 'Kosong'],
                datasets: [{ data: stok, backgroundColor: ['#728156', '#F59E0B', '#E11D48'], borderWidth: 0, spacing: 3, borderRadius: 8, hoverOffset: 4 }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: { display: false },
                    tooltip: Object.assign({}, tooltipBase, {
                        callbacks: { label: function (c) { return c.label + ': ' + fmt(c.parsed) + ' item'; } }
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
                    ctx.font = "800 30px 'Plus Jakarta Sans', sans-serif";
                    ctx.fillText('{{ $pctAman }}%', cx, cy - 8);
                    ctx.fillStyle = '#64748b';
                    ctx.font = "600 11px 'Plus Jakarta Sans', sans-serif";
                    ctx.fillText('Stok Aman', cx, cy + 16);
                    ctx.restore();
                }
            }]
        });
    }
})();
</script>
@endpush
