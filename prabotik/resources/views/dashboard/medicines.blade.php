@extends('layouts.app')

@section('title', 'Katalog 150 Obat & Evaluasi MA')
@section('page_title', 'Katalog Obat & Evaluasi Peramalan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 text-xs font-bold border border-cyan-500/30 mb-2 inline-block">
                    Katalog Lengkap Logistik Puskesmas
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Katalog 150 Obat Aktif & Evaluasi Moving Average</h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                    Evaluasi komparatif akurasi model SMA-2, SMA-3, dan WMA-3 berdasarkan metrik MAPE, MAE, dan RMSE pada 22 periode dwibulanan LPLPO UPTD Puskesmas Teluk Sasah.
                </p>
            </div>
            <div class="text-right">
                <span class="text-3xl font-extrabold text-medical-400 block">{{ $totalCount }}</span>
                <span class="text-xs text-slate-400 uppercase font-semibold">Total Sediaan Obat</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('medicines') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
            
            <div class="sm:col-span-5">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari sediaan obat..." class="w-full pl-9 pr-4 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 focus:border-transparent transition">
                </div>
            </div>

            <div class="sm:col-span-3">
                <select name="prioritas" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 focus:border-transparent transition">
                    <option value="">Semua Prioritas</option>
                    <option value="Prioritas 1" {{ request('prioritas') == 'Prioritas 1' ? 'selected' : '' }}>Prioritas 1 (Vital)</option>
                    <option value="Prioritas 2" {{ request('prioritas') == 'Prioritas 2' ? 'selected' : '' }}>Prioritas 2 (Esensial)</option>
                    <option value="Prioritas 3" {{ request('prioritas') == 'Prioritas 3' ? 'selected' : '' }}>Prioritas 3 (Non-Esensial)</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="momentum" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 focus:border-transparent transition">
                    <option value="">Semua Momentum</option>
                    <option value="Bullish" {{ request('momentum') == 'Bullish' ? 'selected' : '' }}>Bullish</option>
                    <option value="Bearish" {{ request('momentum') == 'Bearish' ? 'selected' : '' }}>Bearish</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex space-x-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs transition shadow-xs">
                    Terapkan
                </button>
                @if(request()->hasAny(['q', 'prioritas', 'momentum']))
                <a href="{{ route('medicines') }}" class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">
                    Reset
                </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Medicines Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-5 py-4">Nama Sediaan Obat</th>
                        <th class="px-3 py-4 text-center">Satuan</th>
                        <th class="px-4 py-4 text-center">Model Terbaik</th>
                        <th class="px-4 py-4 text-right">Galat MAPE</th>
                        <th class="px-4 py-4 text-right">MAE</th>
                        <th class="px-4 py-4 text-right">RMSE</th>
                        <th class="px-4 py-4 text-right">Prediksi P5</th>
                        <th class="px-4 py-4 text-center">Momentum MACD</th>
                        <th class="px-5 py-4 text-center">Prioritas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($medicines as $index => $med)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-3.5 text-slate-400 font-semibold">{{ $medicines->firstItem() + $index }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">
                            {{ $med->nama_obat }}
                            @if($med->is_censored_adjusted)
                            <span class="block text-[10px] text-amber-600 font-normal italic mt-0.5">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i>Disesuaikan dari Permintaan Tersensor P4
                            </span>
                            @endif
                        </td>
                        <td class="px-3 py-3.5 text-center text-slate-500">{{ $med->satuan }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $med->model_terbaik }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-right font-extrabold {{ $med->mape_terbaik <= 20 ? 'text-emerald-600' : ($med->mape_terbaik <= 50 ? 'text-amber-600' : 'text-slate-600') }}">
                            {{ number_format($med->mape_terbaik, 2, ',', '.') }}%
                        </td>
                        <td class="px-4 py-3.5 text-right text-slate-600">{{ number_format($med->mae, 1, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-right text-slate-600">{{ number_format($med->rmse, 1, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-right font-bold text-medical-700">{{ number_format($med->prediksi_P5, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ str_contains($med->momentum_status, 'Bullish') || str_contains($med->momentum_status, 'Rebound') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $med->momentum_status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ str_contains($med->prioritas_kategori, 'Prioritas 1') ? 'bg-rose-50 text-rose-700 border border-rose-200' : (str_contains($med->prioritas_kategori, 'Prioritas 2') ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                {{ $med->prioritas_kategori }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-8 text-center text-slate-400">
                            Tidak ditemukan obat yang sesuai dengan kriteria pencarian.
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
