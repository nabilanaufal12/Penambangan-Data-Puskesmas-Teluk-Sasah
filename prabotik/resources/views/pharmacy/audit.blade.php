@extends('layouts.pharmacy')

@section('title', 'Pemeriksaan Obat Kadaluarsa & Pencegahan Kekosongan Stok')
@section('page_title', 'Pemeriksaan Obat Kadaluarsa & Pencegahan Kekosongan Stok')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg">
        <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30 mb-2 inline-block">
            Pengendalian Mutu &amp; Rotasi Obat Kadaluarsa
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Pemeriksaan Obat Kadaluarsa &amp; Riwayat Kekosongan Stok</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Data pemeriksaan resmi 138 berita acara obat kadaluarsa/rusak kurun waktu 2023–2025 di UPTD Puskesmas Teluk Sasah guna memastikan obat yang mendekati masa kadaluarsa lebih dulu didistribusikan ke pasien.
        </p>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-navy-950">482 Kali</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Riwayat Kekosongan Stok</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Tercatat pada 292 jenis obat</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-navy-950">{{ $totalItems }} Item</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Item Berita Acara ED</p>
                <p class="text-[11px] text-slate-400 mt-0.5">35 (2023) + 55 (2024) + 48 (2025)</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-2xl font-bold">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-red-700">Rp {{ number_format($totalLoss, 0, ',', '.') }}</span>
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Kerugian Finansial</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Sumber: APBD & DAK Farmasi</p>
            </div>
        </div>
    </div>

    <!-- Yearly Breakdown Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach($yearlyLoss as $yl)
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-bold text-slate-400 uppercase">Tahun Anggaran {{ $yl->tahun }}</span>
            <p class="text-xl font-black text-rose-600 font-mono mt-1">Rp {{ number_format($yl->total, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-500 mt-0.5 block">{{ $yl->count }} Berita Acara Pemusnahan</span>
        </div>
        @endforeach
    </div>

    <!-- Expired Medicines Master Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-700">Daftar Fisik Berita Acara Obat Kadaluarsa (ED)</h3>
                <p class="text-[11px] text-slate-400">Total 138 Sediaan Obat Dimusnahkan</p>
            </div>
            <a href="{{ route('pharmacy.entry', ['tab' => 'kadaluarsa']) }}" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center space-x-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Catat Berita Acara Baru</span>
            </a>
        </div>
        <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200 sticky top-0">
                    <tr>
                        <th class="px-5 py-3.5">Tahun</th>
                        <th class="px-5 py-3.5">Nama Sediaan Obat</th>
                        <th class="px-3 py-3.5 text-center">Satuan</th>
                        <th class="px-4 py-3.5 text-right">Jumlah Fisik</th>
                        <th class="px-4 py-3.5 text-right">Harga Satuan</th>
                        <th class="px-4 py-3.5 text-right font-black text-rose-600">Subtotal Kerugian</th>
                        <th class="px-4 py-3.5">Sumber Anggaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($expiredMedicines as $em)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-5 py-3.5 font-bold text-slate-900 font-mono">{{ $em->tahun }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $em->nama_obat }}</td>
                        <td class="px-3 py-3.5 text-center text-slate-500">{{ $em->satuan }}</td>
                        <td class="px-4 py-3.5 text-right font-mono font-bold">{{ number_format($em->jumlah_kadaluarsa, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-right font-mono text-slate-500">Rp {{ number_format($em->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-right font-mono font-black text-rose-600">Rp {{ number_format($em->sub_total_kerugian, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-slate-500 text-[11px]">{{ $em->sumber_anggaran }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
