@extends('layouts.pharmacy')

@section('title', 'Beranda Gudang & Logistik Obat')
@section('page_title', 'Operasional Gudang & Persediaan Obat Fisik')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- PHARMACY HERO BANNER -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30">
                    <i class="fa-solid fa-warehouse mr-1"></i>Instalasi Farmasi UPTD Puskesmas Teluk Sasah
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Pusat Kendali Operasional Farmasi & Stok Obat
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Sistem pemantauan stok fisik gudang (*Stock on Hand*), deteksi defisit di bawah batas aman (*Safety Stock*), dan otomasi rekomendasi lembar LPLPO dwibulanan Periode 5 (2026).
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('pharmacy.entry') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition flex items-center">
                    <i class="fa-solid fa-pen-to-square mr-2"></i>
                    <span>Pusat Entri Data</span>
                </a>
                <a href="{{ route('pharmacy.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center">
                    <i class="fa-solid fa-print mr-2 text-emerald-300"></i>
                    <span>Cetak Lembar LPLPO</span>
                </a>
            </div>
        </div>

        <!-- 4 OPERATIONAL LOGISTIC COUNTERS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800/80">
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Total Sediaan Aktif</span>
                    <i class="fa-solid fa-capsules text-emerald-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-white mt-1">{{ $stats['total_obat_aktif'] }} <span class="text-xs font-normal text-slate-300">Item</span></p>
                <span class="text-[10px] text-emerald-400 mt-1 block">Tercatat di Formularium</span>
            </div>

            <div class="bg-white/5 rounded-2xl p-4 border border-rose-500/20 bg-rose-500/5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-rose-300 uppercase font-semibold">Stok Kosong Fisik</span>
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-rose-400 mt-1">{{ $stats['stok_kosong_count'] }} <span class="text-xs font-normal text-rose-200">Item</span></p>
                <span class="text-[10px] text-rose-300 mt-1 block">Wajib Masuk Usulan Segera</span>
            </div>

            <div class="bg-white/5 rounded-2xl p-4 border border-amber-500/20 bg-amber-500/5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-amber-300 uppercase font-semibold">Stok Kritis (≤ SS)</span>
                    <i class="fa-solid fa-bell text-amber-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-amber-400 mt-1">{{ $stats['stok_kritis_count'] }} <span class="text-xs font-normal text-amber-200">Item</span></p>
                <span class="text-[10px] text-amber-300 mt-1 block">Menembus Buffer Cadangan</span>
            </div>

            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Total Usulan Order P5</span>
                    <i class="fa-solid fa-cart-flatbed text-cyan-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-cyan-400 mt-1">{{ number_format($stats['total_rekomendasi_unit'], 0, ',', '.') }} <span class="text-xs font-normal text-slate-300">Unit</span></p>
                <span class="text-[10px] text-cyan-300 mt-1 block">Periode September-Oktober</span>
            </div>
        </div>
    </div>

    <!-- STATUS PENGAJUAN KE KEPALA PUSKESMAS -->
    @if($stats['is_approved'])
    <div class="bg-emerald-50 border border-emerald-300 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-3.5">
            <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 text-lg shadow-md shadow-emerald-600/30">
                <i class="fa-solid fa-stamp"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-emerald-950 text-sm">
                    Usulan Rencana Pengadaan P5 (2026) Telah Disahkan Kepala Puskesmas!
                </h3>
                <p class="text-xs text-emerald-800 mt-0.5 leading-relaxed">
                    Dokumen usulan LPLPO telah disetujui secara digital oleh <strong>dr. Bambang Utoyo, M.M.</strong> dan siap dicetak untuk dikirim ke Instalasi Farmasi Dinas Kesehatan Kabupaten Bintan.
                </p>
            </div>
        </div>
        <a href="{{ route('pharmacy.print') }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition shrink-0">
            Cetak Lembar Pengesahan &rarr;
        </a>
    </div>
    @else
    <div class="bg-amber-50 border border-amber-300 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-3.5">
            <div class="w-10 h-10 rounded-2xl bg-amber-600 text-white flex items-center justify-center shrink-0 text-lg shadow-md shadow-amber-600/30">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-amber-950 text-sm">
                    Status Usulan Pengadaan P5: Menunggu Otorisasi Kepala Puskesmas
                </h3>
                <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">
                    Kalkulasi usulan order sebanyak <strong>{{ number_format($stats['total_rekomendasi_unit'], 0, ',', '.') }} unit obat</strong> telah selesai disusun dan siap ditelaah oleh Kepala UPTD Puskesmas pada portal eksekutif.
                </p>
            </div>
        </div>
        <a href="{{ route('pharmacy.procurement') }}" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md shadow-amber-600/20 transition shrink-0">
            Review Usulan Order &rarr;
        </a>
    </div>
    @endif

    <!-- 2 MAIN OPERATIONAL COLUMNS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Daftar Obat Kritis Butuh Pengadaan Segera -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Prioritas Restock Fisik Gudang (Defisit)</h3>
                            <p class="text-[11px] text-slate-500">Sediaan dengan stok gudang di bawah batas Safety Stock</p>
                        </div>
                    </div>
                    <a href="{{ route('pharmacy.procurement') }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700">
                        Lihat 150 Obat &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-100 text-slate-600 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Nama Sediaan</th>
                                <th class="py-3 px-3 text-right">Sisa Fisik</th>
                                <th class="py-3 px-3 text-right">Safety Stock</th>
                                <th class="py-3 px-3 text-right">Usulan Order</th>
                                <th class="py-3 px-4 text-center">Status Buffer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @foreach($criticalMedicines as $cm)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $cm->nama_obat }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $cm->satuan }} &bull; {{ $cm->model_terbaik }}</div>
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-black {{ $cm->sisa_stok == 0 ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ number_format($cm->sisa_stok, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-slate-500">
                                    {{ number_format($cm->safety_stock, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-black text-emerald-600">
                                    +{{ number_format($cm->rekomendasi_total, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($cm->sisa_stok == 0)
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        Stok Kosong
                                    </span>
                                    @else
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Buffer Kritis
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Perhitungan didasarkan pada rumus standar Kemenkes RI: $\text{Order} = (\text{Prediksi} + \text{SS}) - \text{Sisa Stok}$.</span>
                    <a href="{{ route('pharmacy.print') }}" target="_blank" class="font-bold text-slate-700 hover:text-slate-900">
                        Cetak LPLPO &rarr;
                    </a>
                </div>
            </div>

            <!-- Deteksi Tren Momentum MACD Farmasi -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Deteksi Lonjakan Permintaan (MACD Fast Moving)</h3>
                            <p class="text-[11px] text-slate-500">Obat yang menunjukkan percepatan konsumsi di ruang pelayanan</p>
                        </div>
                    </div>
                    <a href="{{ route('pharmacy.macd') }}" class="text-[11px] font-bold text-amber-600 hover:text-amber-700">
                        Selengkapnya &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($bullishMedicines as $bm)
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-slate-900 block truncate max-w-[180px]">{{ $bm->nama_obat }}</span>
                            <span class="text-[10px] text-emerald-600 font-bold">{{ $bm->momentum_status }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 block">Usulan Order</span>
                            <span class="font-mono font-bold text-slate-800">{{ number_format($bm->rekomendasi_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Pintasan Operasional Farmasi & Audit Kadaluarsa (FEFO) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Quick Action Shortcuts -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-extrabold text-slate-900 text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-bolt text-emerald-600"></i>
                    <span>Tindakan Operasional Farmasi</span>
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('pharmacy.entry', ['tab' => 'lplpo']) }}" class="p-3.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-900 transition block">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm mb-2 shadow-xs">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div class="font-bold text-xs">Update Stok LPLPO</div>
                        <div class="text-[10px] text-emerald-700 mt-0.5">Input pemakaian & SOH</div>
                    </a>

                    <a href="{{ route('pharmacy.entry', ['tab' => 'obat']) }}" class="p-3.5 rounded-2xl bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-900 transition block">
                        <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center text-sm mb-2 shadow-xs">
                            <i class="fa-solid fa-pills"></i>
                        </div>
                        <div class="font-bold text-xs">Daftar Obat Baru</div>
                        <div class="text-[10px] text-purple-700 mt-0.5">Tambah ke formularium</div>
                    </a>

                    <a href="{{ route('pharmacy.entry', ['tab' => 'kadaluarsa']) }}" class="p-3.5 rounded-2xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-900 transition block">
                        <div class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center text-sm mb-2 shadow-xs">
                            <i class="fa-solid fa-calendar-xmark"></i>
                        </div>
                        <div class="font-bold text-xs">Catat Berita Acara ED</div>
                        <div class="text-[10px] text-amber-700 mt-0.5">Input obat rusak/kadaluarsa</div>
                    </a>

                    <a href="{{ route('pharmacy.print') }}" target="_blank" class="p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-900 transition block">
                        <div class="w-8 h-8 rounded-xl bg-slate-800 text-white flex items-center justify-center text-sm mb-2 shadow-xs">
                            <i class="fa-solid fa-print"></i>
                        </div>
                        <div class="font-bold text-xs">Cetak Lembar Kerja</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Format Dinkes Bintan</div>
                    </a>
                </div>
            </div>

            <!-- Monitoring Obat Kadaluarsa & Rusak (FEFO) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2">
                        <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-hourglass-end"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wide">Audit Obat Kadaluarsa (ED)</h3>
                    </div>
                    <a href="{{ route('pharmacy.audit') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700">
                        Detail Audit &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    @foreach($recentExpired as $re)
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-slate-900 block truncate max-w-[170px]">{{ $re->nama_obat }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $re->tahun }} &bull; {{ number_format($re->jumlah_kadaluarsa, 0, ',', '.') }} {{ $re->satuan }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-400 block">Kerugian</span>
                            <span class="font-mono font-bold text-rose-600">Rp {{ number_format($re->sub_total_kerugian, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Total Akumulasi Kerugian:</span>
                    <span class="font-black text-rose-600 font-mono">Rp {{ number_format($stats['total_ed_loss'], 0, ',', '.') }}</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
