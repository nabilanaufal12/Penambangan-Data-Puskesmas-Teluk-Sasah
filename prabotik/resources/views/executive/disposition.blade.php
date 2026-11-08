@extends('layouts.executive')

@section('title', 'Persetujuan & Pengesahan Usulan Permintaan Obat P5')
@section('page_title', 'Persetujuan & Pengesahan Lembar Permintaan Obat P5')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-amber-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-500/30 inline-block">
                    Persetujuan Kepala Puskesmas
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Persetujuan &amp; Pengesahan Usulan Permintaan Obat P5</h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Penelaahan dan pengesahan resmi atas usulan permintaan 184.077 unit sediaan obat periode September–Oktober 2026 yang diajukan oleh Bagian Farmasi UPTD Puskesmas Teluk Sasah ke Dinas Kesehatan Kabupaten Bintan.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('executive.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center">
                    <i class="fa-solid fa-print mr-2 text-amber-300"></i>
                    <span>Cetak Lembar LPLPO Resmi</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Notification Message -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-300 rounded-2xl p-4 text-emerald-900 text-xs flex items-center space-x-3 shadow-xs">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="font-bold flex-1 leading-relaxed">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Status Card & Approval Action -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 font-mono">NOMOR DOKUMEN: LPLPO/TS/2026/P5</span>
                <h2 class="text-lg font-black text-slate-900 mt-1">Status Disposisi Pengesahan Kepala Puskesmas</h2>
            </div>
            <div>
                @if($isApproved)
                <span class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-800 border border-emerald-300 font-extrabold text-xs inline-flex items-center space-x-2">
                    <i class="fa-solid fa-stamp text-emerald-600"></i>
                    <span>TELAH DISAHKAN SECARA DIGITAL</span>
                </span>
                @else
                <span class="px-4 py-2 rounded-xl bg-amber-100 text-amber-800 border border-amber-300 font-extrabold text-xs inline-flex items-center space-x-2">
                    <i class="fa-solid fa-clock-rotate-left text-amber-600"></i>
                    <span>MENUNGGU PERSETUJUAN KEPALA FASKES</span>
                </span>
                @endif
            </div>
        </div>

        <!-- 3 Summary Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <span class="text-[11px] text-slate-400 font-bold uppercase">Total Unit Sediaan Obat</span>
                <p class="text-2xl font-black text-slate-900 font-mono mt-1">{{ number_format($totalOrderUnit, 0, ',', '.') }}</p>
                <span class="text-[10px] text-slate-500 mt-0.5 block">Usulan 150 Sediaan Aktif</span>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <span class="text-[11px] text-slate-400 font-bold uppercase">Estimasi Kebutuhan Biaya</span>
                <p class="text-2xl font-black text-amber-700 font-mono mt-1">Rp {{ number_format($estimasiAnggaran, 0, ',', '.') }}</p>
                <span class="text-[10px] text-slate-500 mt-0.5 block">Alokasi APBD / DAK Farmasi Bintan</span>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <span class="text-[11px] text-slate-400 font-bold uppercase">Tingkat Kesiapan Dokumen</span>
                <p class="text-2xl font-black text-emerald-700 font-mono mt-1">100%</p>
                <span class="text-[10px] text-slate-500 mt-0.5 block">Verifikasi Fisik & Imputasi Selesai</span>
            </div>
        </div>

        <!-- Official Signatures Box -->
        <div class="p-5 bg-amber-50/50 rounded-2xl border border-amber-200/80 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 bg-white rounded-xl border border-slate-200">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">Penelaah / Pengelola Obat:</span>
                    <p class="font-extrabold text-slate-900 text-sm mt-1">Apt. Siti Rahmawati, S.Farm</p>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5">SIP. 19890415/SIPA/2018/021</p>
                    <span class="inline-block mt-2 px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                        Selesai Diteliti & Diajukan
                    </span>
                </div>

                <div class="p-4 bg-white rounded-xl border border-amber-300">
                    <span class="text-[10px] text-amber-600 uppercase font-bold">Pejabat Pengesah (Kepala Puskesmas):</span>
                    <p class="font-extrabold text-slate-900 text-sm mt-1">dr. Bambang Utoyo, M.M.</p>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5">NIP. 19780512 200604 1 008</p>
                    @if($isApproved)
                    <span class="inline-block mt-2 px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-bold shadow-xs">
                        <i class="fa-solid fa-stamp mr-1"></i>Disahkan Digital pada {{ date('d F Y') }}
                    </span>
                    @else
                    <span class="inline-block mt-2 px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                        Menunggu Tanda Tangan Kapus
                    </span>
                    @endif
                </div>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-amber-200">
                <p class="text-xs text-slate-600">
                    Dengan mengklik tombol persetujuan, Anda selaku Kepala UPTD Puskesmas menyatakan usulan pengadaan resmi disahkan.
                </p>
                <div class="flex items-center space-x-2 shrink-0">
                    <a href="{{ route('executive.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Dokumen</span>
                    </a>
                    @if(!$isApproved)
                    <form method="POST" action="{{ route('executive.approve') }}">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-stamp"></i>
                            <span>Setujui Usulan P5 (Sahkan)</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Table of 10 Highest Value Items for Scrutiny -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-sm">10 Alokasi Sediaan Terbesar yang Membutuhkan Otorisasi Belanja</h3>
                <p class="text-[11px] text-slate-500">Mencakup 78,4% total volume pengadaan dwibulanan P5</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Sediaan Obat</th>
                        <th class="py-3 px-3 text-center">Satuan</th>
                        <th class="py-3 px-3 text-right">Sisa Stok Fisik</th>
                        <th class="py-3 px-3 text-right">Prediksi Kebutuhan</th>
                        <th class="py-3 px-3 text-right">Safety Stock 95%</th>
                        <th class="py-3 px-4 text-right font-black text-amber-700">Rekomendasi Alokasi</th>
                        <th class="py-3 px-4">Indikasi Klinis / Kebijakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($topProcurement as $tp)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-900">
                            {{ $tp->nama_obat }}
                        </td>
                        <td class="py-3 px-3 text-center text-slate-500 font-mono">{{ $tp->satuan }}</td>
                        <td class="py-3 px-3 text-right font-mono font-bold text-slate-800">
                            {{ number_format($tp->sisa_stok, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-3 text-right font-mono text-slate-600">
                            {{ number_format($tp->prediksi_P5, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-3 text-right font-mono text-slate-500">
                            +{{ number_format($tp->safety_stock, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-black text-amber-700 text-sm">
                            {{ number_format($tp->rekomendasi_total, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-[11px]">
                            @if(str_contains(strtolower($tp->nama_obat), 'fero'))
                            Program Intervensi Anemia Bumil & Rematri (Prioritas Nasional)
                            @elseif(str_contains(strtolower($tp->nama_obat), 'amlodipin'))
                            Terapi Hipertensi Primer Kronis (I10) Poli Umum
                            @elseif(str_contains(strtolower($tp->nama_obat), 'parasetamol'))
                            Antipiretik ISPA (J00) — Penyakit Terbanyak Puskesmas
                            @elseif(str_contains(strtolower($tp->nama_obat), 'metformin'))
                            Pengendalian Gula Darah Diabetes Melitus Tipe 2 (E11)
                            @else
                            Sediaan Esensial Pelayanan Rawat Jalan
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
