@extends('layouts.pharmacy')

@section('title', 'Lembar Permintaan Obat ke Dinkes (Periode P5)')
@section('page_title', 'Perencanaan Lembar Permintaan Obat Periode 2026-P5')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header Page Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Hitungan Otomatis Kebutuhan Logistik</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Usulan Permintaan Obat Periode 2026-P5</h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Lembar usulan permintaan resmi ke Gudang Farmasi Dinas Kesehatan Kabupaten Bintan dihitung otomatis dari rata-rata pemakaian riil, cadangan pengaman (stok penyangga), dan sisa fisik di gudang.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('pharmacy.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center">
                    <i class="fa-solid fa-print mr-2 text-emerald-300"></i>
                    <span>Cetak Lembar LPLPO</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Paracetamol Special Alert Card -->
    @if($paracetamol)
    <div id="koreksi-section" class="bg-amber-50 border border-amber-300/80 rounded-2xl p-5 shadow-xs scroll-mt-28 {{ request('filter') === 'koreksi' ? 'ring-2 ring-amber-500' : '' }}">
        <div class="flex items-start space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h3 class="font-extrabold text-amber-950 text-sm">
                        Penyesuaian Akibat Obat Sempat Kosong: {{ $paracetamol->nama_obat }}
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-200 text-amber-900 w-fit">
                        Stok 0 Terkoreksi
                    </span>
                </div>
                <p class="text-xs text-amber-900/80 mt-1.5 leading-relaxed">
                    Pada periode Juli–Agustus 2026, pemakaian tercatat 0 butir karena obat sempat habis total di gudang. Jika dihitung mentah tanpa koreksi, usulan permintaan akan anjlok terlalu rendah (hanya 3.222 butir) dan dapat memicu kekosongan obat kembali. Sistem telah menyesuaikan perkiraan kebutuhan menjadi <strong>{{ number_format($paracetamol->prediksi_P5, 0, ',', '.') }} tablet</strong> dengan cadangan pengaman sebesar <strong>{{ number_format($paracetamol->safety_stock, 0, ',', '.') }} tablet</strong>.
                </p>
                <div class="mt-3 flex items-center space-x-4 text-xs font-bold text-amber-950">
                    <span>Total Rekomendasi Pengadaan: <span class="text-base text-matcha-700 font-extrabold">{{ number_format($paracetamol->rekomendasi_total, 0, ',', '.') }} tablet</span></span>
                    <span>&bull;</span>
                    <span class="text-matcha-700">Status: Aman dari Resiko Kehabisan Obat ISPA</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Toolbar Filter Cepat Sub-Modul & Pencarian -->
    <div class="space-y-3">
        <!-- Quick Filter Pills -->
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-xs font-bold text-slate-500 mr-1"><i class="fa-solid fa-filter text-matcha-600 mr-1"></i>Filter Cepat:</span>
            <a href="{{ route('pharmacy.procurement') }}" class="px-3.5 py-1.5 rounded-full font-bold text-xs transition {{ empty(request('filter')) && empty(request('prioritas')) ? 'bg-matcha-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-matcha-50 border border-slate-200' }}">
                Semua Usulan ({{ $totalCount ?? 150 }})
            </a>
            <a href="{{ route('pharmacy.procurement', ['filter' => 'kritis']) }}" class="px-3.5 py-1.5 rounded-full font-bold text-xs transition {{ request('filter') === 'kritis' ? 'bg-matcha-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-matcha-50 border border-slate-200' }}">
                <i class="fa-solid fa-triangle-exclamation mr-1 text-amber-500"></i>Perlu Usulan P5 ({{ $kritisCount ?? 96 }})
            </a>
            <a href="{{ route('pharmacy.procurement', ['filter' => 'vital']) }}" class="px-3.5 py-1.5 rounded-full font-bold text-xs transition {{ request('filter') === 'vital' ? 'bg-matcha-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-matcha-50 border border-slate-200' }}">
                <i class="fa-solid fa-bolt mr-1 text-rose-500"></i>Vital P1 ({{ $vitalCount ?? 10 }})
            </a>
            <a href="{{ route('pharmacy.procurement', ['filter' => 'koreksi']) }}" class="px-3.5 py-1.5 rounded-full font-bold text-xs transition {{ request('filter') === 'koreksi' ? 'bg-matcha-700 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-matcha-50 border border-slate-200' }}">
                <i class="fa-solid fa-clock-rotate-left mr-1 text-matcha-600"></i>Terkoreksi Stockout ({{ $koreksiCount ?? 1 }})
            </a>
        </div>

        <!-- Filter & Search Toolbar Form -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('pharmacy.procurement') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif

                <div class="sm:col-span-6">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama obat (misal: Parasetamol, Metformin)..." class="w-full pl-9 pr-4 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600 transition">
                    </div>
                </div>

                <div class="sm:col-span-4">
                    <select name="prioritas" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600 transition">
                        <option value="">Semua Kategori Prioritas</option>
                        <option value="Prioritas 1" {{ request('prioritas') == 'Prioritas 1' || request('filter') == 'vital' ? 'selected' : '' }}>Prioritas 1 (Vital / Fast-Moving)</option>
                        <option value="Prioritas 2" {{ request('prioritas') == 'Prioritas 2' ? 'selected' : '' }}>Prioritas 2 (Esensial / Stabil)</option>
                        <option value="Prioritas 3" {{ request('prioritas') == 'Prioritas 3' ? 'selected' : '' }}>Prioritas 3 (Buffer / Penunjang)</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex space-x-2">
                    <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-matcha-700 hover:bg-matcha-800 text-white font-bold text-xs transition shadow-xs">
                        Filter
                    </button>
                    @if(request()->hasAny(['q', 'prioritas', 'filter']))
                    <a href="{{ route('pharmacy.procurement') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition" title="Reset Semua Filter">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Banner Informasi Filter Aktif -->
        @if(request()->hasAny(['filter', 'q', 'prioritas']))
        <div class="p-3.5 bg-matcha-50 border border-matcha-200/80 rounded-2xl flex items-center justify-between text-xs">
            <div class="flex items-center gap-2 text-matcha-900 font-medium">
                <i class="fa-solid fa-circle-check text-matcha-700"></i>
                @if(request('filter') === 'kritis')
                    <span>Filter Aktif: Menampilkan <strong>{{ $medicines->total() }} sediaan</strong> yang memerlukan alokasi order pengadaan P5 (Alokasi > 0 unit).</span>
                @elseif(request('filter') === 'vital')
                    <span>Filter Aktif: Menampilkan <strong>{{ $medicines->total() }} sediaan</strong> kategori Prioritas 1 (Vital &amp; Fast-Moving).</span>
                @elseif(request('filter') === 'koreksi')
                    <span>Filter Aktif: Menampilkan sediaan yang perhitungannya disesuaikan akibat kekosongan fisik (stockout).</span>
                @else
                    <span>Filter pencarian aktif. Ditemukan <strong>{{ $medicines->total() }} sediaan</strong> obat.</span>
                @endif
            </div>
            <a href="{{ route('pharmacy.procurement') }}" class="font-bold text-matcha-800 hover:text-matcha-900 underline text-[11px] shrink-0 ml-2">
                Tampilkan Semua (150) &times;
            </a>
        </div>
        @endif
    </div>

    <!-- Procurement Master Table -->
    <div id="procurement-table" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden scroll-mt-24">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Nama Sediaan Obat</th>
                        <th class="px-3 py-4 text-center">Satuan</th>
                        <th class="px-3 py-4 text-center">Model Terbaik</th>
                        <th class="px-4 py-4 text-right">Prediksi ($F_{t+1}$)</th>
                        <th class="px-4 py-4 text-right text-amber-600">Safety Stock (95%)</th>
                        <th class="px-4 py-4 text-right text-slate-600">Sisa Stok Fisik</th>
                        <th class="px-5 py-4 text-right bg-emerald-50 text-emerald-950 font-extrabold text-sm">Alokasi Order ($Q$)</th>
                        <th class="px-4 py-4 text-center">Sinyal MACD</th>
                        <th class="px-4 py-4 text-center">Kategori Prioritas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($medicines as $index => $med)
                    <tr class="hover:bg-slate-50/80 transition {{ $med->is_censored_adjusted ? 'bg-amber-50/20' : '' }}">
                        <td class="px-5 py-3.5 text-slate-400 font-semibold">{{ $medicines->firstItem() + $index }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">
                            {{ $med->nama_obat }}
                            @if($med->is_censored_adjusted)
                            <span class="block text-[10px] text-amber-700 font-normal italic mt-0.5">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i>Disesuaikan dari Permintaan Tersensor Stockout P4
                            </span>
                            @endif
                        </td>
                        <td class="px-3 py-3.5 text-center text-slate-500">{{ $med->satuan }}</td>
                        <td class="px-3 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">
                                {{ $med->model_terbaik }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-right font-semibold text-slate-700">
                            {{ number_format($med->prediksi_P5, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5 text-right font-semibold text-amber-600">
                            +{{ number_format($med->safety_stock, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3.5 text-right font-semibold text-slate-500">
                            {{ number_format($med->sisa_stok, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-extrabold bg-emerald-50/40 text-emerald-800 text-sm">
                            @if($med->rekomendasi_total == 0)
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-xs font-bold">0 (Stok Aman)</span>
                            @else
                                {{ number_format($med->rekomendasi_total, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ str_contains($med->momentum_status, 'Bullish') || str_contains($med->momentum_status, 'Rebound') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $med->momentum_status }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ str_contains($med->prioritas_kategori, 'Tinggi') ? 'bg-rose-50 text-rose-700 border border-rose-200' : (str_contains($med->prioritas_kategori, 'Sedang') ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                {{ $med->prioritas_kategori }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-8 text-center text-slate-400">
                            Tidak ditemukan obat yang cocok dengan filter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $medicines->links() }}
        </div>
    </div>

</div>
@endsection
