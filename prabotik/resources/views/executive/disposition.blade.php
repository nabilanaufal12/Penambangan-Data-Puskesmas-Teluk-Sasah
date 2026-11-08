@extends('layouts.executive')

@section('title', 'Persetujuan & Pengesahan Usulan Permintaan Obat P5')
@section('page_title', 'Persetujuan & Pengesahan Lembar Permintaan Obat P5')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7 space-y-6">
    
    <!-- Header Banner -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-matcha-100 text-matcha-900 text-[10px] font-extrabold uppercase tracking-wider">
                    Otoritas Pengesahan LPLPO
                </span>
                <span class="text-xs font-mono text-slate-400">NOMOR: LPLPO/TS/2026/P5</span>
            </div>
            <h1 class="mt-2 text-2xl sm:text-[30px] leading-tight font-extrabold tracking-tight text-ink">
                Persetujuan &amp; Pengesahan Usulan Permintaan Obat P5
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 max-w-2xl leading-relaxed">
                Penelaahan dan pengesahan resmi atas usulan permintaan {{ number_format($totalOrderUnit, 0, ',', '.') }} unit sediaan obat periode September–Oktober 2026 yang diajukan oleh Bagian Farmasi UPTD Puskesmas Teluk Sasah ke Dinas Kesehatan Kabupaten Bintan.
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('executive.print') }}" target="_blank" class="px-5 py-2.5 rounded-full bg-white hover:bg-matcha-50 text-matcha-900 text-xs font-bold shadow-float border border-slate-200/80 transition flex items-center gap-2">
                <i class="fa-solid fa-print text-matcha-700"></i>
                <span>Cetak Lembar LPLPO Resmi</span>
            </a>
            @if(!$isApproved)
            <form method="POST" action="{{ route('executive.approve') }}" class="inline">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white text-xs font-bold shadow-md shadow-matcha-700/25 transition flex items-center gap-2">
                    <i class="fa-solid fa-stamp"></i>
                    <span>Sahkan Usulan P5</span>
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Notification Message -->
    @if(session('success'))
    <div class="bg-matcha-50 border border-matcha-300 rounded-2xl p-4 text-matcha-900 text-xs flex items-center space-x-3 shadow-xs">
        <div class="w-8 h-8 rounded-xl bg-matcha-700 text-white flex items-center justify-center font-bold shrink-0">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="font-bold flex-1 leading-relaxed">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Status Card & Approval Action -->
    <div id="status-card" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-float space-y-6 scroll-mt-28">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 font-mono">NOMOR DOKUMEN: LPLPO/TS/2026/P5</span>
                <h2 class="text-lg font-black text-ink mt-1">Status Disposisi Pengesahan Kepala Puskesmas</h2>
            </div>
            <div>
                @if($isApproved)
                <span class="px-4 py-2 rounded-full bg-matcha-100 text-matcha-900 border border-matcha-300 font-extrabold text-xs inline-flex items-center space-x-2">
                    <i class="fa-solid fa-stamp text-matcha-700"></i>
                    <span>TELAH DISAHKAN SECARA DIGITAL</span>
                </span>
                @else
                <span class="px-4 py-2 rounded-full bg-amber-100 text-amber-800 border border-amber-300 font-extrabold text-xs inline-flex items-center space-x-2">
                    <i class="fa-solid fa-clock-rotate-left text-amber-600"></i>
                    <span>MENUNGGU PERSETUJUAN KEPALA FASKES</span>
                </span>
                @endif
            </div>
        </div>

        <!-- 3 Summary Metrics -->
        <div id="metrics-card" class="grid grid-cols-1 sm:grid-cols-3 gap-4 scroll-mt-28">
            <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80">
                <span class="text-[11px] text-slate-400 font-bold uppercase">Total Unit Sediaan Obat</span>
                <p class="text-2xl font-black text-ink font-mono mt-1">{{ number_format($totalOrderUnit, 0, ',', '.') }}</p>
                <span class="text-[10px] text-slate-500 mt-0.5 block">Usulan 150 Sediaan Aktif</span>
            </div>

            <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80">
                <span class="text-[11px] text-slate-400 font-bold uppercase">Estimasi Kebutuhan Biaya</span>
                <p class="text-2xl font-black text-matcha-900 font-mono mt-1">Rp {{ number_format($estimasiAnggaran, 0, ',', '.') }}</p>
                <span class="text-[10px] text-slate-500 mt-0.5 block">Alokasi APBD / DAK Farmasi Bintan</span>
            </div>

            <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80">
                <span class="text-[11px] text-slate-400 font-bold uppercase">Tingkat Kesiapan Dokumen</span>
                <p class="text-2xl font-black text-matcha-700 font-mono mt-1">100%</p>
                <span class="text-[10px] text-slate-500 mt-0.5 block">Verifikasi Fisik &amp; Imputasi Selesai</span>
            </div>
        </div>

        <!-- Official Signatures Box -->
        <div id="signatures-box" class="p-5 bg-matcha-50/70 rounded-2xl border border-matcha-200/80 space-y-4 scroll-mt-28">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Penelaah / Pengelola Obat:</span>
                    <p class="font-extrabold text-slate-900 text-sm mt-1">Apt. Siti Rahmawati, S.Farm</p>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5">SIP. 19890415/SIPA/2018/021</p>
                    <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full bg-matcha-100 text-matcha-800 text-[10px] font-bold border border-matcha-200">
                        <i class="fa-solid fa-circle-check mr-1 text-matcha-600"></i>Selesai Diteliti &amp; Diajukan
                    </span>
                </div>

                <div class="p-4 bg-white rounded-2xl border {{ $isApproved ? 'border-matcha-300 ring-2 ring-matcha-300/50' : 'border-amber-300' }} shadow-xs">
                    <span class="text-[10px] {{ $isApproved ? 'text-matcha-700' : 'text-amber-600' }} uppercase font-bold tracking-wider">Pejabat Pengesah (Kepala Puskesmas):</span>
                    <p class="font-extrabold text-slate-900 text-sm mt-1">dr. Bambang Utoyo, M.M.</p>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5">NIP. 19780512 200604 1 008</p>
                    @if($isApproved)
                    <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full bg-matcha-700 text-white text-[10px] font-bold shadow-xs">
                        <i class="fa-solid fa-stamp mr-1"></i>Disahkan Digital pada {{ date('d F Y') }}
                    </span>
                    @else
                    <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                        <i class="fa-solid fa-hourglass-half mr-1 text-amber-500"></i>Menunggu Tanda Tangan Kapus
                    </span>
                    @endif
                </div>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-matcha-200">
                <p class="text-xs text-slate-600">
                    Dengan mengklik tombol persetujuan, Anda selaku Kepala UPTD Puskesmas menyatakan usulan pengadaan resmi disahkan.
                </p>
                <div class="flex items-center space-x-2 shrink-0">
                    <a href="{{ route('executive.print') }}" target="_blank" class="px-4 py-2.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Dokumen</span>
                    </a>
                    @if(!$isApproved)
                    <form method="POST" action="{{ route('executive.approve') }}">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white text-xs font-bold shadow-md shadow-matcha-700/30 transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-stamp"></i>
                            <span>Setujui Usulan P5 (Sahkan)</span>
                        </button>
                    </form>
                    @else
                    <span class="px-4 py-2 rounded-full bg-matcha-100 text-matcha-800 text-xs font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-matcha-600"></i><span>Usulan Telah Disahkan</span>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Table of Procurement Items -->
    <div id="disposition-table" class="bg-white rounded-3xl border border-slate-200/80 shadow-float overflow-hidden scroll-mt-28">
        <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-extrabold text-ink text-sm">10 Alokasi Sediaan Terbesar yang Membutuhkan Otorisasi Belanja</h3>
                <p class="text-[11px] text-slate-500">Mencakup sediaan prioritas vital &amp; fast-moving pengadaan dwibulanan P5</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('executive.print') }}" target="_blank" class="px-3.5 py-1.5 rounded-full bg-white hover:bg-matcha-50 text-slate-700 hover:text-matcha-900 text-xs font-bold transition border border-slate-200/80 shadow-xs flex items-center gap-1.5">
                    <i class="fa-solid fa-file-invoice text-xs text-matcha-600"></i>
                    <span>Buka Format Cetak LPLPO 150 Obat &rarr;</span>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-100/80 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Sediaan Obat</th>
                        <th class="py-3 px-3 text-center">Satuan</th>
                        <th class="py-3 px-3 text-right">Sisa Stok Fisik</th>
                        <th class="py-3 px-3 text-right">Prediksi Kebutuhan</th>
                        <th class="py-3 px-3 text-right">Safety Stock 95%</th>
                        <th class="py-3 px-4 text-right font-black text-matcha-900">Rekomendasi Alokasi</th>
                        <th class="py-3 px-4">Indikasi Klinis / Kebijakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($topProcurement as $tp)
                    <tr class="hover:bg-matcha-50/40 transition">
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
                        <td class="py-3 px-4 text-right font-mono font-black text-matcha-900 text-sm">
                            {{ number_format($tp->rekomendasi_total, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-[11px]">
                            @if(str_contains(strtolower($tp->nama_obat), 'fero'))
                            Program Intervensi Anemia Bumil &amp; Rematri (Prioritas Nasional)
                            @elseif(str_contains(strtolower($tp->nama_obat), 'amlodipin'))
                            Terapi Hipertensi Primer Kronis (I10) Poli Umum
                            @elseif(str_contains(strtolower($tp->nama_obat), 'parasetamol'))
                            Antipiretik ISPA (J00) &mdash; Penyakit Terbanyak Puskesmas
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
