@extends('layouts.executive')

@section('title', 'Laporan & Nilai Kerugian Obat Kadaluarsa')
@section('page_title', 'Laporan & Pemeriksaan Kerugian Obat Kadaluarsa / Rusak')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header Banner -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-900 text-[10px] font-extrabold uppercase tracking-wider">
                    Pemeriksaan &amp; Audit Faskes
                </span>
                <span class="text-xs font-mono text-slate-400">SUMBER: BERITA ACARA ED</span>
            </div>
            <h1 class="mt-2 text-2xl sm:text-[30px] leading-tight font-extrabold tracking-tight text-ink">
                Laporan &amp; Pemeriksaan Kerugian Obat Rusak / Kadaluarsa
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 max-w-2xl leading-relaxed">
                Rekapitulasi resmi atas total 138 berita acara pemusnahan obat kadaluarsa senilai Rp {{ number_format($totalLoss, 0, ',', '.') }} kurun waktu 2023–2025 dari APBD dan DAK Farmasi Kabupaten Bintan.
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('executive.dashboard') }}" class="px-5 py-2.5 rounded-full bg-white hover:bg-matcha-50 text-matcha-900 text-xs font-bold shadow-float border border-slate-200/80 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-matcha-700"></i>
                <span>Kembali ke Ringkasan</span>
            </a>
            <a href="{{ route('executive.disposition') }}" class="px-5 py-2.5 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white text-xs font-bold shadow-md shadow-matcha-700/25 transition flex items-center gap-2">
                <i class="fa-solid fa-stamp"></i>
                <span>Persetujuan Obat P5</span>
            </a>
        </div>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-sack-xmark"></i>
            </div>
            <div>
                <span class="text-2xl font-black text-rose-700 font-mono">Rp {{ number_format($totalLoss, 0, ',', '.') }}</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Akumulasi Kerugian (3 Thn)</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-slate-900 font-mono">{{ $totalItems }} Berita Acara</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Batch Fisik Dimusnahkan</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-file-shield"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-slate-900 font-mono">100% Legal</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Status Kelengkapan BA Pemusnahan</p>
            </div>
        </div>
    </div>

    <!-- Yearly Trends Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach($yearlyLoss as $yl)
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase">Tahun Anggaran {{ $yl->tahun }}</span>
            <p class="text-2xl font-black text-rose-600 font-mono">Rp {{ number_format($yl->total, 0, ',', '.') }}</p>
            <div class="flex items-center justify-between text-xs text-slate-500 pt-1 border-t border-slate-100">
                <span>Jumlah Item:</span>
                <span class="font-bold text-slate-800">{{ $yl->count }} Sediaan</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Policy Recommendations Banner -->
    <div class="bg-amber-50 rounded-2xl p-5 border border-amber-200 text-xs text-amber-950 space-y-2">
        <div class="font-extrabold flex items-center space-x-2 text-sm text-amber-900">
            <i class="fa-solid fa-lightbulb text-amber-600"></i>
            <span>Arahan Kebijakan Kepala UPTD Puskesmas Teluk Sasah:</span>
        </div>
        <p class="leading-relaxed text-amber-900/90">
            Peningkatan kerugian pada tahun 2025 menjadi <strong>Rp 21.833.852,-</strong> disebabkan penerimaan drop alokasi dinas yang mendekati masa kadaluarsa (di bawah 12 bulan). Selaku Kuasa Pengguna Anggaran (KPA), diinstruksikan kepada Bagian Farmasi untuk:
        </p>
        <ul class="list-disc list-inside space-y-1 text-amber-900/80 font-medium pl-1">
            <li>Menolak atau mencatat berita acara retur atas penerimaan obat dari IFK yang memiliki sisa masa simpan kurang dari 6 bulan.</li>
            <li>Memperketat kartu stok gudang dengan prinsip FEFO (*First Expired, First Out*).</li>
            <li>Melakukan relokasi sediaan slow-moving ke puskesmas jejaring sebelum masa kadaluarsa jatuh tempo.</li>
        </ul>
    </div>

    <!-- Master Expired Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 text-sm">Daftar Audit Fisik Berita Acara Pemusnahan Obat Rusak / ED</h3>
            <span class="text-xs text-slate-400">Arsip Pertanggungjawaban Keuangan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Tahun</th>
                        <th class="py-3 px-4">Nama Sediaan Obat</th>
                        <th class="py-3 px-3 text-center">Satuan</th>
                        <th class="py-3 px-3 text-right">Jumlah Fisik</th>
                        <th class="py-3 px-3 text-right">Harga Satuan</th>
                        <th class="py-3 px-4 text-right font-black text-rose-600">Subtotal Kerugian</th>
                        <th class="py-3 px-4">Sumber Anggaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @foreach($expiredList as $el)
                    <tr class="hover:bg-slate-50">
                        <td class="py-2.5 px-4 font-mono font-bold text-slate-800">{{ $el->tahun }}</td>
                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $el->nama_obat }}</td>
                        <td class="py-2.5 px-3 text-center text-slate-500">{{ $el->satuan }}</td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold">{{ number_format($el->jumlah_kadaluarsa, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-3 text-right font-mono text-slate-500">Rp {{ number_format($el->harga_satuan, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-4 text-right font-mono font-black text-rose-600">Rp {{ number_format($el->sub_total_kerugian, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-4 text-slate-500 text-[11px]">{{ $el->sumber_anggaran }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $expiredList->links() }}
        </div>
    </div>

</div>
@endsection
