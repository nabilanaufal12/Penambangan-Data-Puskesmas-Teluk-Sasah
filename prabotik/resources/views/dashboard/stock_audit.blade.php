@extends('layouts.app')

@section('title', 'Audit Stockout & Kadaluarsa')
@section('page_title', 'Audit Stockout & Kerugian Kadaluarsa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-amber-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8">
        <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-500/30 mb-2 inline-block">
            Bukti Empiris Masalah Riil Mitra Faskes
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Audit Kekosongan Stok (*Stockout*) & Kerugian Kadaluarsa</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Kuantifikasi komprehensif atas 482 kejadian kekosongan stok (*understock*) serta 138 item fisik Berita Acara Pemusnahan Obat Kadaluarsa senilai Rp 43.348.626,- pada kurun waktu 2023–2025.
        </p>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-navy-950">482 Kali</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Kejadian Kekosongan Stok Nyata</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Tercatat pada 292 jenis obat/BMHP</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-navy-950">{{ $totalItems }} Item</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Item Berita Acara Kadaluarsa</p>
                <p class="text-[11px] text-slate-400 mt-0.5">35 (2023) + 55 (2024) + 48 (2025)</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-red-700">Rp {{ number_format($totalLoss, 0, ',', '.') }}</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Kerugian Finansial</p>
                <p class="text-[11px] text-slate-400 mt-0.5">APBD & DAK Farmasi Terbuang</p>
            </div>
        </div>
    </div>

    <!-- Expired Medicines Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-700">Daftar Audit Fisik Berita Acara Pemusnahan Obat Rusak & Kadaluarsa (`lap ED`)</h3>
            <span class="text-[11px] text-slate-500">Tervalidasi 100% Lembar Fisik</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-4 py-3.5 text-center">Tahun Berita Acara</th>
                        <th class="px-5 py-3.5">Nama Sediaan Obat / BMHP</th>
                        <th class="px-3 py-3.5 text-center">Satuan</th>
                        <th class="px-4 py-3.5 text-right">Jumlah Kadaluarsa</th>
                        <th class="px-4 py-3.5 text-right">Harga Satuan</th>
                        <th class="px-5 py-3.5 text-right font-extrabold text-red-700 bg-red-50/30">Sub-Total Kerugian</th>
                        <th class="px-4 py-3.5 text-center">Sumber Anggaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($expiredItems as $index => $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-3 text-slate-400">{{ $expiredItems->firstItem() + $index }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded font-bold text-[10px] bg-slate-100 text-slate-700">
                                {{ $item->tahun }}
                            </span>
                        </td>
                        <td class="px-5 py-3 font-bold text-slate-900">{{ $item->nama_obat }}</td>
                        <td class="px-3 py-3 text-center text-slate-500">{{ $item->satuan }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-slate-700">{{ number_format($item->jumlah_kadaluarsa, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-slate-600">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right font-extrabold text-red-700 bg-red-50/20">
                            Rp {{ number_format($item->sub_total_kerugian, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center text-slate-500 text-[10px]">{{ $item->sumber_anggaran }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-slate-400">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $expiredItems->links() }}
        </div>
    </div>

</div>
@endsection
