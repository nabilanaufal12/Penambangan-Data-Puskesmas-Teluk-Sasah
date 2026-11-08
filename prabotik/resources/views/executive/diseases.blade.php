@extends('layouts.executive')

@section('title', 'Laporan Morbiditas SIMPUS')
@section('page_title', 'Analisis Beban 10 Penyakit Terbanyak (SIMPUS)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header Banner -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-matcha-100 text-matcha-900 text-[10px] font-extrabold uppercase tracking-wider">
                    Data Epidemiologi Faskes
                </span>
                <span class="text-xs font-mono text-slate-400">SUMBER: SIMPUS RAWAT JALAN</span>
            </div>
            <h1 class="mt-2 text-2xl sm:text-[30px] leading-tight font-extrabold tracking-tight text-ink">
                Profil Beban 10 Penyakit Terbanyak SIMPUS
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 max-w-2xl leading-relaxed">
                Analisis data kunjungan pasien rawat jalan pada sistem SIMPUS UPTD Puskesmas Teluk Sasah guna mendukung pengambilan kebijakan alokasi obat dan program preventif-promotif.
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('executive.correlations') }}" class="px-5 py-2.5 rounded-full bg-white hover:bg-matcha-50 text-matcha-900 text-xs font-bold shadow-float border border-slate-200/80 transition flex items-center gap-2">
                <i class="fa-solid fa-notes-medical text-matcha-700"></i>
                <span>Cek Standar Terapi Obat</span>
            </a>
            <a href="{{ route('executive.disposition') }}" class="px-5 py-2.5 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white text-xs font-bold shadow-md shadow-matcha-700/25 transition flex items-center gap-2">
                <i class="fa-solid fa-stamp"></i>
                <span>Persetujuan Obat P5</span>
            </a>
        </div>
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
