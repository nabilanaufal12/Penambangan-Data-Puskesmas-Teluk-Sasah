@extends('layouts.app')

@section('title', 'Benchmarking Machine Learning')
@section('page_title', 'Evaluasi Benchmarking Machine Learning')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-purple-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8">
        <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 text-xs font-bold border border-purple-500/30 mb-2 inline-block">
            Validasi Rigor 10-Fold Walk-Forward
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Benchmarking Machine Learning vs Baseline Moving Average</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Pengujian model prediktif supervised global (HistGradientBoosting & Ridge) berhadapan dengan model statistik deret waktu pada panel 148 obat aktif dengan skema <em>Expanding Window Walk-Forward</em> (bebas kebocoran data masa depan).
        </p>
    </div>

    <!-- Comparative Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-700">Hasil Evaluasi 10-Fold Walk-Forward (Periode Uji: 2025-P1 s/d 2026-P4)</h3>
            <span class="text-[11px] text-slate-500">Panel 148 Obat Aktif Puskesmas</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-4">Model / Algoritma</th>
                        <th class="px-4 py-4 text-center">Kategori Model</th>
                        <th class="px-4 py-4 text-right font-bold text-slate-800">WAPE (Rata-rata Galat)</th>
                        <th class="px-4 py-4 text-right font-bold">Bias Persentase</th>
                        <th class="px-4 py-4 text-right">Deviasi Standar (SD WAPE)</th>
                        <th class="px-5 py-4">Karakteristik & Dampak Operasional di Puskesmas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($benchmarks as $bm)
                    <tr class="hover:bg-slate-50 transition {{ str_contains($bm->model_name, 'WMA') ? 'bg-emerald-50/40' : (str_contains($bm->model_name, 'Hist') ? 'bg-purple-50/20' : '') }}">
                        <td class="px-5 py-4 font-bold text-slate-900 text-sm">
                            {{ $bm->model_name }}
                            @if(str_contains($bm->model_name, 'WMA'))
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-600 text-white ml-1.5">Model Terpilih</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $bm->kategori == 'Machine Learning' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                {{ $bm->kategori }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right font-extrabold text-sm {{ $bm->wape < 0.55 ? 'text-purple-700' : ($bm->wape < 0.65 ? 'text-slate-800' : 'text-slate-500') }}">
                            {{ number_format($bm->wape * 100, 1) }}%
                        </td>
                        <td class="px-4 py-4 text-right font-extrabold text-sm {{ str_contains($bm->bias_percentage, '-') ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $bm->bias_percentage }}
                        </td>
                        <td class="px-4 py-4 text-right text-slate-500 font-mono">{{ number_format($bm->stabilitas_sd, 3) }}</td>
                        <td class="px-5 py-4 text-xs text-slate-700">
                            {{ $bm->interpretasi }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Academic Insights Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-chart-column"></i>
            </div>
            <h4 class="font-bold text-slate-900 text-sm">1. Tingginya Kebisingan Data Permintaan</h4>
            <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                WAPE terbaik pada ~53% membuktikan bahwa variabilitas pemakaian obat puskesmas sangat dipengaruhi oleh droping logistik dinas yang tidak konstan, bukan semata-mata fungsi matematika deterministik.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h4 class="font-bold text-slate-900 text-sm">2. Bahaya Fatal Bias Negatif (-9.2%)</h4>
            <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                HistGradientBoosting secara rata-rata meramal 9.2% lebih rendah dari kebutuhan sebenarnya. Dalam operasional obat primer, peramalan yang terlalu rendah langsung memicu krisis kekosongan stok (*stockout*).
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h4 class="font-bold text-slate-900 text-sm">3. Keunggulan WMA-3 + Safety Stock</h4>
            <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                WMA-3 memiliki bias positif alami (+4.3%) yang bertindak sebagai penyangga stok. Dipadukan dengan Safety Stock 95%, kebijakan ini menjadi solusi paling aman, andal, dan mudah dipahami (*explainable*).
            </p>
        </div>

    </div>

    <!-- Deep Learning Justification -->
    <div class="bg-slate-900 text-slate-300 rounded-2xl p-6 border border-slate-800">
        <h4 class="font-bold text-white text-sm flex items-center">
            <i class="fa-solid fa-brain mr-2 text-medical-400"></i>
            Mengapa Arsitektur Deep Learning (LSTM / Transformers) Dihindari?
        </h4>
        <p class="text-xs mt-2 leading-relaxed text-slate-400">
            Arsitektur jaringan saraf berulang seperti LSTM, GRU, atau Temporal Fusion Transformer memiliki jutaan parameter yang membutuhkan puluhan ribu observasi kontinu. Pada data panel dwibulanan N=22 periode (hanya ~3,5 siklus tahunan), model Deep Learning dipastikan akan mengalami <em>overfitting katastropik</em>, tidak mampu menangani data non-stasioner akibat intervensi kekosongan pasokan fisik, dan tidak dapat dipertanggungjawabkan di hadapan dewan penguji akademik (*unexplainable black box*).
        </p>
    </div>

</div>
@endsection
