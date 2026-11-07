@extends('layouts.app')

@section('title', 'Rekomendasi Pengadaan 2026-P5')
@section('page_title', 'Rekomendasi Pengadaan 2026-P5')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header Page Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-medical-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30 mb-3">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Keputusan Alokasi Logistik Siap Eksekusi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Rekomendasi Alokasi Pengadaan Periode 2026-P5</h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-2xl leading-relaxed">
                    Perencanaan kuantitas pemesanan resmi ke Dinas Kesehatan Kabupaten Bintan untuk periode September–Oktober 2026 berbasis integrasi peramalan WMA, proteksi Safety Stock 95%, dan eliminasi overstock.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center">
                    <i class="fa-solid fa-print mr-2"></i>
                    <span>Cetak Lembar LPLPO</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Paracetamol Special Alert Card -->
    @if($paracetamol)
    <div class="bg-amber-50 border border-amber-300/80 rounded-2xl p-5 mb-8 shadow-xs">
        <div class="flex items-start space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h3 class="font-extrabold text-amber-950 text-sm">
                        Koreksi Permintaan Tersensor: {{ $paracetamol->nama_obat }}
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-200 text-amber-900 w-fit">
                        Censored Demand Adjusted
                    </span>
                </div>
                <p class="text-xs text-amber-900/80 mt-1.5 leading-relaxed">
                    Pada periode 2026-P4 (Juli–Agustus 2026), pemakaian tercatat 0 butir akibat kekosongan fisik total (stok habis). Tanpa koreksi, ramalan naif jatuh ke <strong>3.222 butir</strong>. Melalui imputasi ilmiah (5.850 butir), proyeksi peramalan resmi SMA-2 disesuaikan menjadi <strong>{{ number_format($paracetamol->prediksi_P5, 0, ',', '.') }} tablet</strong> dengan Safety Stock 95% sebesar <strong>{{ number_format($paracetamol->safety_stock, 0, ',', '.') }} tablet</strong>.
                </p>
                <div class="mt-3 flex items-center space-x-4 text-xs font-bold text-amber-950">
                    <span>Total Rekomendasi Pengadaan Resmi: <span class="text-base text-emerald-700 font-extrabold">{{ number_format($paracetamol->rekomendasi_total, 0, ',', '.') }} tablet</span></span>
                    <span>&bull;</span>
                    <span class="text-emerald-700">Status: Aman dari Resiko Kehabisan Obat ISPA</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('procurement') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
            
            <div class="sm:col-span-6">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama obat (misal: Parasetamol, Metformin)..." class="w-full pl-9 pr-4 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 focus:border-transparent transition">
                </div>
            </div>

            <div class="sm:col-span-4">
                <select name="prioritas" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 focus:border-transparent transition">
                    <option value="">Semua Kategori Prioritas</option>
                    <option value="Prioritas 1" {{ request('prioritas') == 'Prioritas 1' ? 'selected' : '' }}>Prioritas 1 (Vital / Fast-Moving)</option>
                    <option value="Prioritas 2" {{ request('prioritas') == 'Prioritas 2' ? 'selected' : '' }}>Prioritas 2 (Esensial / Stabil)</option>
                    <option value="Prioritas 3" {{ request('prioritas') == 'Prioritas 3' ? 'selected' : '' }}>Prioritas 3 (Penunjang / Non-Esensial)</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs transition shadow-xs">
                    Filter
                </button>
                @if(request()->hasAny(['q', 'prioritas']))
                <a href="{{ route('procurement') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">
                    Reset
                </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Procurement Master Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
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
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ str_contains($med->prioritas_kategori, 'Prioritas 1') ? 'bg-rose-50 text-rose-700 border border-rose-200' : (str_contains($med->prioritas_kategori, 'Prioritas 2') ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
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

        <!-- Pagination -->
        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $medicines->links() }}
        </div>
    </div>

</div>
@endsection
