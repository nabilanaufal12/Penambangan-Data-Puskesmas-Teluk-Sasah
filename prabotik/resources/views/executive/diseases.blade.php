@extends('layouts.executive')

@section('title', 'Laporan Morbiditas SIMPUS')
@section('page_title', 'Analisis Beban 10 Penyakit Terbanyak (SIMPUS)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-amber-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg">
        <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-500/30 mb-2 inline-block">
            Data Epidemiologi Wilayah Kerja Puskesmas
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Profil Beban 10 Penyakit Terbanyak SIMPUS</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Analisis data kunjungan pasien rawat jalan pada sistem SIMPUS UPTD Puskesmas Teluk Sasah guna mendukung pengambilan kebijakan alokasi obat dan program preventif-promotif.
        </p>
    </div>

    <!-- Top 5 Disease Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach($topDiseases->take(5) as $td)
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm space-y-2">
            <span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-100 text-slate-700">
                {{ $td->kode_icd }}
            </span>
            <h3 class="font-bold text-slate-900 text-xs truncate" title="{{ $td->nama_penyakit }}">
                {{ $td->nama_penyakit }}
            </h3>
            <div class="pt-1 flex items-baseline justify-between">
                <span class="text-xl font-black text-amber-700 font-mono">
                    {{ number_format($td->total_pasien, 0, ',', '.') }}
                </span>
                <span class="text-[10px] text-slate-400 font-bold">
                    {{ $td->persentase }}%
                </span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 rounded-full" style="width: {{ $td->persentase }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Top 10 Disease Detailed Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-sm">Daftar 10 Besar Morbiditas Penyakit Rawat Jalan</h3>
                <p class="text-[11px] text-slate-500">Total Akumulasi: {{ number_format($totalPasien, 0, ',', '.') }} Pasien Terlayani</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">Peringkat</th>
                        <th class="py-3 px-3">Kode ICD-10</th>
                        <th class="py-3 px-4">Nama Diagnosis Klinis</th>
                        <th class="py-3 px-4">Klaster Kategori</th>
                        <th class="py-3 px-3 text-right">Pasien Laki-laki</th>
                        <th class="py-3 px-3 text-right">Pasien Perempuan</th>
                        <th class="py-3 px-4 text-right font-black text-amber-700">Total Pasien</th>
                        <th class="py-3 px-3 text-right">Proporsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($topDiseases as $index => $td)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-center font-bold text-slate-400">#{{ $index + 1 }}</td>
                        <td class="py-3 px-3 font-mono font-bold text-slate-800">
                            <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">{{ $td->kode_icd }}</span>
                        </td>
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $td->nama_penyakit }}</td>
                        <td class="py-3 px-4 text-slate-500 text-[11px]">{{ $td->klaster_icd }}</td>
                        <td class="py-3 px-3 text-right font-mono text-slate-600">{{ number_format($td->laki, 0, ',', '.') }}</td>
                        <td class="py-3 px-3 text-right font-mono text-slate-600">{{ number_format($td->perempuan, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right font-mono font-black text-amber-700 text-sm">
                            {{ number_format($td->total_pasien, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-3 text-right font-mono font-bold text-slate-600">
                            {{ $td->persentase }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Records Pagination Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
            <h3 class="font-extrabold text-slate-900 text-sm">Log Rekam Morbiditas Bulanan SIMPUS Terkini</h3>
            <p class="text-[11px] text-slate-500">Arsip data kunjungan poli terintegrasi</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Periode</th>
                        <th class="py-3 px-3">Kode</th>
                        <th class="py-3 px-4">Diagnosis</th>
                        <th class="py-3 px-3 text-right">Laki-laki</th>
                        <th class="py-3 px-3 text-right">Perempuan</th>
                        <th class="py-3 px-4 text-right">Total Pasien</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @foreach($recentRecords as $rr)
                    <tr class="hover:bg-slate-50">
                        <td class="py-2.5 px-4 font-mono font-bold text-slate-800">{{ $rr->bulan }} {{ $rr->tahun }}</td>
                        <td class="py-2.5 px-3 font-mono text-[11px] text-slate-600">{{ $rr->kode_icd }}</td>
                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $rr->nama_penyakit }}</td>
                        <td class="py-2.5 px-3 text-right font-mono text-slate-600">{{ number_format($rr->pasien_laki, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-3 text-right font-mono text-slate-600">{{ number_format($rr->pasien_perempuan, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-4 text-right font-mono font-black text-amber-700">{{ number_format($rr->total_pasien, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $recentRecords->links() }}
        </div>
    </div>

</div>
@endsection
