@extends('layouts.executive')

@section('title', 'Kesesuaian Obat & Penyakit (Rasio Resep Poli)')
@section('page_title', 'Kesesuaian Penggunaan Obat terhadap Kasus Penyakit')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-purple-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg">
        <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 text-xs font-bold border border-purple-500/30 mb-2 inline-block">
            Kepatuhan Penggunaan Obat &amp; Standar Pelayanan Medis
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kesesuaian Obat-Penyakit &amp; Rata-Rata Resep Pasien</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Memantau kesesuaian antara jumlah kunjungan pasien penyakit terbanyak dengan jumlah obat yang keluar guna menjaga peresepan obat tetap rasional dan tepat indikasi di poli UPTD Puskesmas Teluk Sasah.
        </p>
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
                        <td class="py-3 px-4 text-right font-mono font-black text-amber-700">
                            {{ $c->rasio_resep_per_pasien }} butir/pasien
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
