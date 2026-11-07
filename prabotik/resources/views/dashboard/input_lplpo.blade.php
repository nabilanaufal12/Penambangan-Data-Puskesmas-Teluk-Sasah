@extends('layouts.app')

@section('title', 'Input / Tutup Buku LPLPO')
@section('page_title', 'Input Realisasi Stok & Tutup Buku LPLPO')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30 mb-2 inline-block">
                    Modul Operasional Farmasi Puskesmas
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Input Realisasi Pemakaian & Tutup Buku Periode</h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                    Formulir entri mandiri bagi petugas pengelola obat puskesmas untuk memperbarui stok fisik gudang, mencatat pemakaian terbaru, dan menghitung otomatis proyeksi kebutuhan periode berikutnya.
                </p>
            </div>
            <a href="{{ route('lplpo.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center shrink-0">
                <i class="fa-solid fa-print mr-2"></i>
                <span>Cetak Lembar Resmi LPLPO</span>
            </a>
        </div>
    </div>

    <!-- Notification Message -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-300 rounded-2xl p-4 mb-6 text-emerald-900 text-xs flex items-center space-x-3 shadow-xs">
        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
        <div class="font-bold">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Form Input Stok Baru -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm sticky top-24">
                <div class="flex items-center space-x-3 pb-4 mb-5 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm">Entri Pembaruan Stok Obat</h3>
                        <p class="text-[11px] text-slate-500">Kalkulasi ulang rekomendasi secara otomatis</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('lplpo.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Sediaan Obat:</label>
                        <select name="medicine_id" id="medSelect" required class="w-full py-2.5 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500">
                            <option value="">-- Pilih Obat --</option>
                            @foreach($medicines as $m)
                            <option value="{{ $m->id }}" data-satuan="{{ $m->satuan }}" data-stok="{{ $m->sisa_stok }}" data-pred="{{ $m->prediksi_P5 }}">
                                {{ $m->nama_obat }} ({{ $m->satuan }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Realisasi Pemakaian:</label>
                            <input type="number" name="pemakaian_terbaru" min="0" step="1" required placeholder="0" class="w-full py-2.5 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 font-mono">
                            <span class="text-[10px] text-slate-400 block mt-1">Butir/botol terpakai di poli</span>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Sisa Stok Fisik Akhir:</label>
                            <input type="number" name="sisa_stok_terbaru" min="0" step="1" required placeholder="0" class="w-full py-2.5 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 font-mono">
                            <span class="text-[10px] text-slate-400 block mt-1">Hasil stock opname fisik gudang</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan / Alasan Khusus (Opsional):</label>
                        <textarea name="keterangan_tambahan" rows="2" placeholder="Contoh: Stok habis total akibat keterlambatan kiriman dinas..." class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-calculator"></i>
                            <span>Simpan & Perbarui Rekomendasi Alokasi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Current Stock Overview Table -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-800">Status Stok Terkini Gudang Farmasi Puskesmas</h3>
                        <p class="text-[11px] text-slate-500">Data live SQLite &mdash; Siap diajukan ke dinas kesehatan</p>
                    </div>
                </div>

                <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-[10px] sticky top-0 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Nama Obat</th>
                                <th class="px-3 py-3 text-center">Satuan</th>
                                <th class="px-4 py-3 text-right">Sisa Stok Fisik</th>
                                <th class="px-4 py-3 text-right">Buffer SS (95%)</th>
                                <th class="px-4 py-3 text-right font-extrabold text-emerald-700">Rekomendasi Order</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($medicines as $med)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $med->nama_obat }}</td>
                                <td class="px-3 py-3 text-center text-slate-500">{{ $med->satuan }}</td>
                                <td class="px-4 py-3 text-right font-mono font-bold {{ $med->sisa_stok == 0 ? 'text-rose-600' : 'text-slate-700' }}">
                                    {{ number_format($med->sisa_stok, 0, ',', '.') }}
                                    @if($med->sisa_stok == 0)
                                        <span class="block text-[9px] text-rose-500 uppercase font-bold">Kosong</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-amber-600 font-semibold">+{{ number_format($med->safety_stock, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono font-extrabold text-emerald-700 text-sm">
                                    {{ number_format($med->rekomendasi_total, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
