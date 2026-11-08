@extends('layouts.executive')

@section('title', 'Kesesuaian Obat & Penyakit (Rasio Resep Poli)')
@section('page_title', 'Kesesuaian Penggunaan Obat terhadap Kasus Penyakit')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Title Banner -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-matcha-100 text-matcha-900 text-[10px] font-extrabold uppercase tracking-wider">
                    Standar Pelayanan Medis
                </span>
                <span class="text-xs font-mono text-slate-400">RASIONALITAS RESEP POLI</span>
            </div>
            <h1 class="mt-2 text-2xl sm:text-[30px] leading-tight font-extrabold tracking-tight text-ink">
                Kesesuaian Obat-Penyakit &amp; Rata-Rata Resep Pasien
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 max-w-2xl leading-relaxed">
                Memantau kesesuaian antara jumlah kunjungan pasien penyakit terbanyak dengan jumlah obat yang keluar guna menjaga peresepan obat tetap rasional dan tepat indikasi di poli UPTD Puskesmas Teluk Sasah.
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('executive.diseases') }}" class="px-5 py-2.5 rounded-full bg-white hover:bg-matcha-50 text-matcha-900 text-xs font-bold shadow-float border border-slate-200/80 transition flex items-center gap-2">
                <i class="fa-solid fa-heart-pulse text-matcha-700"></i>
                <span>Cek Beban SIMPUS</span>
            </a>
            <a href="{{ route('executive.disposition') }}" class="px-5 py-2.5 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white text-xs font-bold shadow-md shadow-matcha-700/25 transition flex items-center gap-2">
                <i class="fa-solid fa-stamp"></i>
                <span>Persetujuan Obat P5</span>
            </a>
        </div>
    </div>

    <!-- Correlation Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 text-sm">Daftar Kesesuaian Obat Terhadap Diagnosis Terbanyak</h3>
            <span class="text-xs text-slate-400 font-mono">Standar Terapi Puskesmas</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Kode ICD</th>
                        <th class="py-3 px-4">Nama Penyakit</th>
                        <th class="py-3 px-4">Nama Sediaan Obat</th>
                        <th class="py-3 px-4">Indikasi Pengobatan</th>
                        <th class="py-3 px-3 text-right">Kekuatan Hubungan</th>
                        <th class="py-3 px-3 text-right">Konsistensi Resep</th>
                        <th class="py-3 px-4 text-right font-black text-amber-700">Rasio Resep/Pasien</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($correlations as $c)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $c->kode_icd }}</td>
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $c->nama_penyakit }}</td>
                        <td class="py-3 px-4 font-bold text-amber-800">{{ $c->nama_obat_standar }}</td>
                        <td class="py-3 px-4 text-slate-600 text-[11px]">{{ $c->indikasi_klinis }}</td>
                        <td class="py-3 px-3 text-right font-mono font-bold {{ $c->pearson_r > 0.3 ? 'text-emerald-600' : 'text-slate-600' }}">
                            {{ number_format($c->pearson_r, 4, ',', '.') }}
                        </td>
                        <td class="py-3 px-3 text-right font-mono text-slate-600">
                            {{ number_format($c->spearman_rho, 4, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-black text-matcha-800">
                            {{ number_format($c->rasio_dosis_per_pasien, 2, ',', '.') }} butir/pasien
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
