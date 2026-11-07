@extends('layouts.app')

@section('title', 'Indikator Momentum MACD Farmasi')
@section('page_title', 'Indikator Momentum MACD Farmasi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-amber-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8">
        <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-500/30 mb-2 inline-block">
            Saran 2 Dosen Pembimbing (Pak Tekad)
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Indikator Momentum MACD Farmasi Puskesmas</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Adaptasi indikator teknikal Gerald Appel (Fast EMA=2, Slow EMA=4, Signal EMA=2) sebagai <em>Early Warning System</em> untuk mendeteksi akselerasi lonjakan resep (Bullish) atau perlambatan/kekosongan stok (Bearish).
        </p>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-navy-950">{{ $bullishCount }}</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Obat Momentum Bullish (Naik)</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-navy-950">{{ $bearishCount }}</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Obat Momentum Bearish (Turun)</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-100 text-medical-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-wave-square"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-navy-950">2 / 4 / 2</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Parameter Dwibulanan</p>
            </div>
        </div>
    </div>

    <!-- MACD Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-700">Daftar Indikator MACD Seluruh Obat Aktif (2026-P4)</h3>
            <span class="text-[11px] text-slate-500">Fast EMA=2, Slow EMA=4, Signal=2</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Nama Sediaan Obat</th>
                        <th class="px-3 py-3.5 text-center">Satuan</th>
                        <th class="px-4 py-3.5 text-right">Nilai MACD</th>
                        <th class="px-4 py-3.5 text-right">Garis Sinyal</th>
                        <th class="px-4 py-3.5 text-right font-extrabold">Histogram</th>
                        <th class="px-5 py-3.5 text-center">Status Momentum</th>
                        <th class="px-5 py-3.5">Implikasi Logistik Farmasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($medicines as $med)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-3.5 font-bold text-slate-900">
                            {{ $med->nama_obat }}
                            @if($med->is_censored_adjusted)
                            <span class="block text-[10px] text-emerald-600 font-normal italic">
                                Sinyal Rebound Positif pasca-imputasi stockout
                            </span>
                            @endif
                        </td>
                        <td class="px-3 py-3.5 text-center text-slate-500">{{ $med->satuan }}</td>
                        <td class="px-4 py-3.5 text-right font-mono text-slate-700">{{ number_format($med->macd_value, 2, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-right font-mono text-slate-500">{{ number_format($med->signal_line, 2, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-right font-mono font-extrabold text-sm {{ $med->histogram >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $med->histogram >= 0 ? '+' : '' }}{{ number_format($med->histogram, 2, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold {{ $med->histogram >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $med->momentum_status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            @if($med->histogram >= 0)
                                Permintaan sedang mengakselerasi; amankan buffer stok pengadaan.
                            @else
                                Permintaan melambat atau ada anomali pasokan; cegah pemesanan berlebih.
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
